<?php

use App\Models\tbautoridade;

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'autoridades'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'autoridades',
        ],
    ],

    'providers' => [
        'autoridades' => [
            'driver' => 'eloquent',
            'model' => tbautoridade::class,
        ],
    ],

    'passwords' => [
        'autoridades' => [
            'provider' => 'autoridades',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];