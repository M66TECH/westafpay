<?php

return [
    'wave' => [
        'base_url' => env('WAVE_BASE_URL', 'https://api.wave.com'),
        'api_key' => env('WAVE_API_KEY'),
        'signing_secret' => env('WAVE_SIGNING_SECRET'),
        'webhook_secret' => env('WAVE_WEBHOOK_SECRET'),
    ],
];
