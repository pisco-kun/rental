<?php

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],
        'admin' => [
            'driver' => 'passport',
            'provider' => 'admin',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\User::class
        ],
        'admin' => [
            'driver' => 'eloquent',
            'model' => \App\Http\Controllers\Admin\Models\Admin::class
        ]
    ]
];