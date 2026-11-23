<?php

return [
    'sms' => [
        'driver' => env('SMS_DRIVER', 'log'),
        'endpoint' => env('SMS_HTTP_ENDPOINT'),
        'token' => env('SMS_HTTP_TOKEN'),
        'auth_header' => env('SMS_HTTP_AUTH_HEADER', 'Authorization'),
        'auth_scheme' => env('SMS_HTTP_AUTH_SCHEME', 'Bearer'),
        'sender' => env('SMS_SENDER_ID'),
        'recipient_key' => env('SMS_HTTP_RECIPIENT_KEY', 'to'),
        'message_key' => env('SMS_HTTP_MESSAGE_KEY', 'message'),
        'sender_key' => env('SMS_HTTP_SENDER_KEY', 'sender'),
        'country_code' => env('SMS_DEFAULT_COUNTRY_CODE', '880'),
        'timeout' => (int) env('SMS_HTTP_TIMEOUT', 15),
    ],
    'postmark' => ['token' => env('POSTMARK_API_TOKEN')],
    'resend' => ['key' => env('RESEND_API_KEY')],
    'ses' => ['key' => env('AWS_ACCESS_KEY_ID'), 'secret' => env('AWS_SECRET_ACCESS_KEY'), 'region' => env('AWS_DEFAULT_REGION', 'us-east-1')],
    'slack' => ['notifications' => ['bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'), 'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL')]],
];
