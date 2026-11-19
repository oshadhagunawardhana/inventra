<?php

return [
    'enabled' => env('WSO2_ENABLED', false),
    'issuer' => env('WSO2_ISSUER'),
    'provider_url' => env('WSO2_PROVIDER_URL', env('WSO2_ISSUER')),
    'client_id' => env('WSO2_CLIENT_ID'),
    'client_secret' => env('WSO2_CLIENT_SECRET'),
    'redirect_uri' => env('WSO2_REDIRECT_URI'),
    'ca_bundle' => env('WSO2_CA_BUNDLE'),
];
