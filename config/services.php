<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'aba' => [
        'mode' => env('ABA_PAYWAY_MODE', 'sandbox'),
        'merchant_id' => env('ABA_PAYWAY_MERCHANT_ID', 'ec438902'),
        'api_key' => env('ABA_PAYWAY_API_KEY', 'fake_api_key_for_testing'),
        'api_url' => env('ABA_PAYWAY_API_URL', 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/purchase'),
        'check_url' => env('ABA_PAYWAY_CHECK_URL', 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/check-transaction-2'),
    ],

];
