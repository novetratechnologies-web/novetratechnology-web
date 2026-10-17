<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'twilio' => [
         'sid' => env('TWILIO_SID'),
         'token' => env('TWILIO_TOKEN'),
         'key' => env('TWILIO_KEY'),
         'secret' => env('TWILIO_SECRET')
      ],

    'hostpinnacle' => [
        'endpoint' => env('HOSTPINNACLE_SMS_ENDPOINT', 'https://smsportal.hostpinnacle.co.ke/SMSApi/send'),
        'api_key' => env('HOSTPINNACLE_API_KEY'),
        'sender_id' => env('HOSTPINNACLE_SENDER_ID'),
        'enabled' => env('SMS_STATUS', 'off') === 'on',
    ],

    'streaming' => [
        'ingest_url' => env('STREAM_INGEST_URL'),
        'playback_url' => env('STREAM_PLAYBACK_URL'),
        'path_prefix' => trim(env('STREAM_PATH_PREFIX', 'live'), '/'),
        'auth_username_prefix' => env('STREAM_AUTH_USERNAME_PREFIX', 'igm-church-'),
        'webhook_token' => env('STREAM_WEBHOOK_TOKEN'),
    ],

];
