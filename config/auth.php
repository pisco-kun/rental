<?php

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'users' => [
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
            'model' => \App\Http\Controllers\Users\Models\Users::class
        ],
        'admin' => [
            'driver' => 'eloquent',
            'model' => \App\Http\Controllers\Admin\Models\Admin::class
        ]
    ]
];