<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SIPENA API Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk integrasi dengan API SIPENA (Sistem Informasi
    | Penggajian Negeri) untuk mengambil data slip gaji ASN/Pegawai.
    |
    */

    'base_url' => env('SIPENA_BASE_URL', 'https://slipgaji.simaru.my.id/api'),

    'api_key' => env('SIPENA_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Request Configuration
    |--------------------------------------------------------------------------
    */

    'timeout' => (int) env('SIPENA_TIMEOUT', 30),

    'retry' => [
        'attempts' => (int) env('SIPENA_RETRY_ATTEMPTS', 3),
        'delay' => (int) env('SIPENA_RETRY_DELAY', 1000),
    ],

    'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'X-API-Key' => env('SIPENA_API_KEY', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Endpoints
    |--------------------------------------------------------------------------
    */

    'endpoints' => [
        'search_slip_gaji' => '/slip',
        'download_slip' => '/slip/download',
        'unit_kerja' => '/satuan-kerja',
        'tujuan_unduh' => '/tujuan-unduh',
        'periode' => '/periode',
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Methods
    |--------------------------------------------------------------------------
    */

    'methods' => [
        'search_slip_gaji' => 'POST',
        'download_slip' => 'POST',
        'unit_kerja' => 'GET',
        'tujuan_unduh' => 'GET',
        'periode' => 'GET',
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Messages
    |--------------------------------------------------------------------------
    */

    'error_messages' => [
        'timeout' => 'Request ke server SIPENA timeout. Silakan coba lagi.',
        'connection_error' => 'Tidak dapat terhubung ke server SIPENA.',
        'unauthorized' => 'API Key SIPENA tidak valid. Silakan periksa konfigurasi.',
        'token_expired' => 'Token SIPENA sudah expired. Silakan login ulang.',
        'not_found' => 'Data slip gaji tidak ditemukan.',
        'server_error' => 'Server SIPENA sedang error. Silakan coba nanti.',
        'invalid_response' => 'Respons dari server SIPENA tidak valid.',
        'rate_limit' => 'Terlalu banyak request. Silakan tunggu sebentar.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */

    'logging' => [
        'enabled' => env('APP_DEBUG', false),
        'channel' => 'daily',
        'level' => 'info',
        'log_api_calls' => true,
        'log_response_data' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */

    'cache' => [
        'unit_kerja' => [
            'enabled' => true,
            'ttl' => 86400,
            'key' => 'sipena_unit_kerja',
        ],
        'tujuan_unduh' => [
            'enabled' => true,
            'ttl' => 86400,
            'key' => 'sipena_tujuan_unduh',
        ],
    ],
];
