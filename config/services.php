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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // sms duniya custom service 

    'smsduniya' => [
        'domain' => env('SMS_DUNIYA_DOMAIN'),
        'username' => env('SMS_DUNIYA_USERNAME'),
        'password' => env('SMS_DUNIYA_PASSWORD'),
        'sender_id' => env('SMS_DUNIYA_SENDER_ID'),
        'template_id' => env('SMS_DUNIYA_TEMPLATE_ID'),
        'entity_id' => env('SMS_DUNIYA_ENTITY_ID'),
    ],

    // wati whatsapp

    'wati' => [
        'endpoint' => env('WATI_ENDPOINT'),
        'access_token' => env('WATI_ACCESS_TOKEN')
    ]


];
