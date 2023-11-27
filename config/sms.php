<?php

return [
    'default' => env('SMS_DRIVER', 'kavehnegar'),

    'drivers' => [
        'kavehnegar' => [
            'api_key' => config('kavenegar.apikey'),
            'base_url' => env('KAVENEGAR_BASE_URL', ''),
            'service' => App\Services\Sms\KavenegarService::class,
        ],
        'example' => [
            'api_key' => env('EXAMPLE_API_KEY', ''),
            'base_url' => env('EXAMPLE_BASE_URL', ''),
            'service' => App\Services\Sms\ExampleSmsService::class,
        ],
    ],
];
