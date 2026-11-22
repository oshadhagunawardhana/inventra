<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Wso2Client;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Wso2AuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        $this->ensureConfigured();
        session()->forget('wso2');
        session()->put('wso2.started_at', time());

        try {
            app(Wso2Client::class)->authenticate();
        } catch (HttpResponseException $exception) {
            throw $exception;
        } catch (Throwable) {
            session()->forget('wso2');

            return $this->failure('WSO2 login is temporarily unavailable. Please try again.');
        }

        return $this->failure('Unable to start WSO2 login.');
    }

    public function callback(Request $request): RedirectResponse
    {
        $this->ensureConfigured();
        $state = session('wso2.openid_connect_state');
        $startedAt = session('wso2.started_at');
        if (! is_string($state) || ! is_string($request->query('state'))
            || ! hash_equals($state, $request->query('state'))
            || ! is_int($startedAt) || $startedAt > time() || time() - $startedAt > 600) {
            session()->forget('wso2');

            return $this->failure('Your WSO2 login session expired or is invalid. Please start again.');
        }

        if ($request->has('error') || ! is_string($request->query('code')) || $request->query('code') === '') {
            session()->forget('wso2');

            return $this->failure('WSO2 login was cancelled or could not be completed.');
        }

        try {
            $client = app(Wso2Client::class);
            if (! $client->authenticate()) {
                return $this->failure('Unable to verify your WSO2 login.');
            }
            $subject = $client->getVerifiedClaims('sub');
            if (! is_string($subject) || $subject === '') {
                return $this->failure('Unable to verify your WSO2 login.');
            }
            $user = User::where('wso2_issuer', config('wso2.issuer'))
                ->where('wso2_subject', $subject)->first();
            if (! $user) {
                return $this->failure('This WSO2 account has no Inventra access. Contact your administrator.');
            }

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        } catch (Throwable) {
            return $this->failure('Unable to verify your WSO2 login. Please start again.');
        } finally {
            session()->forget('wso2');
        }
    }

    private function ensureConfigured(): void
    {
        abort_unless(config('wso2.enabled'), 404);
        foreach (['issuer', 'provider_url', 'client_id', 'client_secret', 'redirect_uri'] as $key) {
            abort_unless(is_string(config('wso2.'.$key)) && config('wso2.'.$key) !== '', 503, 'WSO2 login has not been configured.');
        }
        abort_unless(str_starts_with(config('wso2.issuer'), 'https://'), 503, 'WSO2 requires a trusted HTTPS issuer.');
        abort_unless(str_starts_with(config('wso2.provider_url'), 'https://'), 503, 'WSO2 discovery requires HTTPS.');
    }

    private function failure(string $message): RedirectResponse
    {
        return redirect()->route('login')->withErrors(['wso2' => $message]);
    }
}
