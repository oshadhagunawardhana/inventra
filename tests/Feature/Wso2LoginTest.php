<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Wso2Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Wso2LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.key' => 'base64:'.base64_encode(str_repeat('x', 32))]);
        config(['wso2.enabled' => true, 'wso2.issuer' => 'https://identity.example.test',
            'wso2.provider_url' => 'https://identity.example.test',
            'wso2.client_id' => 'inventra', 'wso2.client_secret' => 'test-secret',
            'wso2.redirect_uri' => 'http://localhost/auth/wso2/callback']);
    }

    public function test_disabled_login_is_hidden_and_unavailable(): void
    {
        config(['wso2.enabled' => false]);
        $this->get('/login')->assertDontSee('Sign in with WSO2');
        $this->get('/auth/wso2')->assertNotFound();
    }

    public function test_missing_configuration_fails_closed(): void
    {
        config(['wso2.client_secret' => null]);
        $this->get('/auth/wso2')->assertStatus(503);
    }

    public function test_authorization_redirect_uses_pkce_and_laravel_session(): void
    {
        $this->app->bind(Wso2Client::class, FakeWso2Client::class);
        $response = $this->get('/auth/wso2');
        $response->assertRedirect();
        parse_str(parse_url($response->headers->get('Location'), PHP_URL_QUERY), $query);
        $this->assertSame('S256', $query['code_challenge_method']);
        $this->assertSame('code', $query['response_type']);
        $this->assertStringContainsString('openid', $query['scope']);
        $response->assertSessionHas('wso2.openid_connect_state', $query['state']);
        $response->assertSessionHas('wso2.openid_connect_nonce', $query['nonce']);
        $response->assertSessionHas('wso2.openid_connect_code_verifier');
    }

    public function test_invalid_state_is_rejected_before_token_exchange(): void
    {
        $this->mock(Wso2Client::class)->shouldNotReceive('authenticate');
        $this->withSession(['wso2' => ['started_at' => time(), 'openid_connect_state' => 'expected']])
            ->get('/auth/wso2/callback?code=code&state=wrong')
            ->assertRedirect('/login')->assertSessionHasErrors('wso2')->assertSessionMissing('wso2');
        $this->assertGuest();
    }

    public function test_expired_login_is_rejected(): void
    {
        $this->withSession(['wso2' => ['started_at' => time() - 601, 'openid_connect_state' => 'expected']])
            ->get('/auth/wso2/callback?code=code&state=expected')->assertSessionHasErrors('wso2');
        $this->assertGuest();
    }

    public function test_signed_token_logs_in_only_a_linked_user_and_callback_cannot_be_replayed(): void
    {
        $user = User::factory()->create();
        $user->forceFill(['wso2_issuer' => config('wso2.issuer'), 'wso2_subject' => 'staff-123'])->save();
        $this->app->bind(Wso2Client::class, FakeWso2Client::class);
        $response = $this->get('/auth/wso2');
        parse_str(parse_url($response->headers->get('Location'), PHP_URL_QUERY), $query);
        $callback = '/auth/wso2/callback?code=code&state='.$query['state'];
        $this->get($callback)->assertRedirect('/dashboard')->assertSessionMissing('wso2');
        $this->assertAuthenticatedAs($user);
        $this->post('/logout')->assertRedirect('/login');
        $this->get($callback)->assertSessionHasErrors('wso2');
        $this->assertGuest();
    }

    public function test_unlinked_identity_cannot_access_inventory_or_create_a_user(): void
    {
        User::factory()->create(['email' => 'staff@example.test']);
        $this->app->bind(Wso2Client::class, FakeWso2Client::class);
        $response = $this->get('/auth/wso2');
        parse_str(parse_url($response->headers->get('Location'), PHP_URL_QUERY), $query);
        $this->get('/auth/wso2/callback?code=code&state='.$query['state'])->assertSessionHasErrors('wso2');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_tampered_token_signature_is_rejected(): void
    {
        $this->app->bind(Wso2Client::class, fn () => new FakeWso2Client(tamper: true));
        $response = $this->get('/auth/wso2');
        parse_str(parse_url($response->headers->get('Location'), PHP_URL_QUERY), $query);
        $this->get('/auth/wso2/callback?code=code&state='.$query['state'])->assertSessionHasErrors('wso2');
        $this->assertGuest();
    }

    public function test_invalid_nonce_and_expired_tokens_are_rejected(): void
    {
        foreach (['nonce', 'exp', 'iss', 'aud'] as $invalidClaim) {
            $this->app->bind(Wso2Client::class, fn () => new FakeWso2Client(invalidClaim: $invalidClaim));
            $response = $this->get('/auth/wso2');
            parse_str(parse_url($response->headers->get('Location'), PHP_URL_QUERY), $query);
            $this->get('/auth/wso2/callback?code=code&state='.$query['state'])->assertSessionHasErrors('wso2');
            $this->assertGuest();
        }
    }

    public function test_link_command_requires_existing_user_and_unique_identity(): void
    {
        $first = User::factory()->create();
        $second = User::factory()->create();
        $this->artisan('wso2:link-user', ['email' => $first->email, 'subject' => 'staff-123'])->assertSuccessful();
        $this->artisan('wso2:link-user', ['email' => $second->email, 'subject' => 'staff-123'])->assertFailed();
        $this->artisan('wso2:link-user', ['email' => 'missing@example.test', 'subject' => 'staff-456'])->assertFailed();
    }
}

class FakeWso2Client extends Wso2Client
{
    private mixed $key;

    public function __construct(private bool $tamper = false, private ?string $invalidClaim = null)
    {
        parent::__construct();
        $this->key = openssl_pkey_new(['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
    }

    protected function fetchURL(string $url, ?string $post_body = null, array $headers = []): string
    {
        $issuer = config('wso2.issuer');
        if (str_contains($url, '.well-known')) {
            return json_encode(['issuer' => $issuer, 'authorization_endpoint' => $issuer.'/authorize',
                'token_endpoint' => $issuer.'/token', 'jwks_uri' => $issuer.'/jwks',
                'token_endpoint_auth_methods_supported' => ['client_secret_basic']]);
        }
        if ($url === $issuer.'/jwks') {
            $rsa = openssl_pkey_get_details($this->key)['rsa'];

            return json_encode(['keys' => [['kty' => 'RSA', 'kid' => 'test-key', 'use' => 'sig',
                'n' => $this->encode($rsa['n']), 'e' => $this->encode($rsa['e'])]]]);
        }
        if ($url === $issuer.'/token') {
            parse_str($post_body, $body);
            if (($body['code_verifier'] ?? null) !== session('wso2.openid_connect_code_verifier')) {
                throw new \RuntimeException('Missing PKCE verifier');
            }
            $claims = ['iss' => $issuer, 'aud' => 'inventra', 'sub' => 'staff-123',
                'nonce' => session('wso2.openid_connect_nonce'), 'iat' => time(), 'exp' => time() + 300];
            if ($this->invalidClaim) {
                $claims[$this->invalidClaim] = $this->invalidClaim === 'exp' ? time() - 3600 : 'wrong';
            }
            $payload = $this->encode(json_encode(['alg' => 'RS256', 'kid' => 'test-key'])).'.'.$this->encode(json_encode($claims));
            openssl_sign($payload, $signature, $this->key, OPENSSL_ALGO_SHA256);
            if ($this->tamper) {
                $signature[0] = chr(ord($signature[0]) ^ 1);
            }

            return json_encode(['access_token' => 'access', 'token_type' => 'Bearer', 'id_token' => $payload.'.'.$this->encode($signature)]);
        }
        throw new \RuntimeException('Unexpected OIDC URL');
    }

    private function encode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
