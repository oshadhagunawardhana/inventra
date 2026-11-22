<?php

namespace App\Services;

use Illuminate\Http\Exceptions\HttpResponseException;
use Jumbojett\OpenIDConnectClient;

class Wso2Client extends OpenIDConnectClient
{
    public function __construct()
    {
        parent::__construct(config('wso2.provider_url'), config('wso2.client_id'), config('wso2.client_secret'));
        $this->setIssuerValidator(fn (string $issuer): bool => $issuer === config('wso2.issuer'));
        $this->providerConfigParam(['code_challenge_methods_supported' => ['S256']]);
        $this->setRedirectURL(config('wso2.redirect_uri'));
        $this->addScope(['profile', 'email']);
        $this->setCodeChallengeMethod('S256');
        $this->setTimeout(15);
        if (config('wso2.ca_bundle')) {
            $this->setCertPath(config('wso2.ca_bundle'));
        }
    }

    public function authenticate(): bool
    {
        $originalRequest = $_REQUEST;
        $_REQUEST = request()->only(['code', 'state', 'error']);
        try {
            return parent::authenticate();
        } finally {
            $_REQUEST = $originalRequest;
        }
    }

    public function redirect(string $url): void
    {
        throw new HttpResponseException(redirect()->away($url));
    }

    protected function getSessionKey(string $key): mixed
    {
        return session()->get('wso2.'.$key, false);
    }

    protected function setSessionKey(string $key, $value): void
    {
        session()->put('wso2.'.$key, $value);
    }

    protected function unsetSessionKey(string $key): void
    {
        session()->forget('wso2.'.$key);
    }

    protected function commitSession(): void
    {
        // Laravel saves the session when its middleware handles the response.
    }

    protected function verifyJWTClaims($claims, ?string $accessToken = null): bool
    {
        return ($this->getIdTokenHeader()->alg ?? null) === 'RS256'
            && isset($claims->nonce, $claims->exp, $claims->iat)
            && is_int($claims->iat)
            && $claims->iat <= time() + 60
            && (! isset($claims->azp) || $claims->azp === config('wso2.client_id'))
            && (! is_array($claims->aud ?? null) || count($claims->aud) <= 1 || isset($claims->azp))
            && parent::verifyJWTClaims($claims, $accessToken);
    }
}
