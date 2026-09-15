<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Content Security Policy
    |--------------------------------------------------------------------------
    |
    | Keep CSP opt-in because BeikeShop plugins can add third-party scripts,
    | frames, payment widgets and image hosts. Enable it in production only
    | after validating every installed plugin against the selected policy.
    |
    */
    'csp' => [
        'enabled' => env('SECURITY_CSP_ENABLED', false),
        'policy'  => env(
            'SECURITY_CSP_POLICY',
            "default-src 'self'; img-src 'self' data: https:; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline'; font-src 'self' data:; connect-src 'self' https:; frame-ancestors 'self'; base-uri 'self'; form-action 'self'"
        ),
    ],
];
