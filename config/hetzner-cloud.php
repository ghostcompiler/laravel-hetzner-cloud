<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Hetzner Cloud API Token
    |--------------------------------------------------------------------------
    |
    | Your Hetzner Cloud API project token. You can generate one in the
    | Hetzner Cloud Console.
    |
    */
    'token' => env('HETZNER_CLOUD_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Base API URL
    |--------------------------------------------------------------------------
    |
    | The base URL of the Hetzner Cloud API.
    |
    */
    'base_url' => env('HETZNER_CLOUD_BASE_URL', 'https://api.hetzner.cloud/v1'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The default request timeout in seconds.
    |
    */
    'timeout' => (int) env('HETZNER_CLOUD_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for rate limit retries and server error retries.
    |
    */
    'retries' => (int) env('HETZNER_CLOUD_RETRIES', 3),
    'retry_backoff' => (int) env('HETZNER_CLOUD_RETRY_BACKOFF', 100), // ms multiplier

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | When enabled, requests and responses will be logged using Laravel's
    | Log service.
    |
    */
    'logging' => [
        'enabled' => (bool) env('HETZNER_CLOUD_LOGGING_ENABLED', false),
        'channel' => env('HETZNER_CLOUD_LOGGING_CHANNEL'),
    ],
];
