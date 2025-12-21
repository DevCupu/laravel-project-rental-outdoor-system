<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'whatsapp' => [
        'booking_number' => env('BOOKING_WHATSAPP_NUMBER', '6281234567890'),
    ],

    'payment' => [
        'cash_note' => env('PAYMENT_CASH_NOTE', 'Bayar cash saat pengambilan di gudang Bontang Outdoor.'),
        'transfer' => [
            'bank' => env('PAYMENT_TRANSFER_BANK', 'BCA'),
            'account_number' => env('PAYMENT_TRANSFER_ACCOUNT_NUMBER', '1234567890'),
            'account_name' => env('PAYMENT_TRANSFER_ACCOUNT_NAME', 'Bontang Outdoor'),
            'instructions' => env('PAYMENT_TRANSFER_INSTRUCTIONS', 'Setelah transfer, kirim bukti pembayaran melalui WhatsApp agar booking segera diproses.'),
        ],
    ],

];
