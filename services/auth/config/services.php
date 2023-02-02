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
        'domain' => env('AUTH_MAILGUN_DOMAIN'),
        'secret' => env('AUTH_MAILGUN_SECRET'),
        'endpoint' => env('AUTH_MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('AUTH_POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AUTH_AWS_ACCESS_KEY_ID'),
        'secret' => env('AUTH_AWS_SECRET_ACCESS_KEY'),
        'region' => env('AUTH_AWS_DEFAULT_REGION', 'us-east-1'),
    ],

];
