<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Social Media Configuration
    |--------------------------------------------------------------------------
    | Facebook, Twitter, Instagram এবং অন্যান্য সোশ্যাল মিডিয়া কনফিগারেশন
    */

    'facebook' => [
        'app_id' => env('FACEBOOK_APP_ID'),
        'app_secret' => env('FACEBOOK_APP_SECRET'),
        'app_version' => env('FACEBOOK_APP_VERSION', 'v18.0'),
        'redirect_url' => env('FACEBOOK_REDIRECT_URL', env('APP_URL') . '/auth/facebook/callback'),
        'permissions' => ['email', 'public_profile'],
        'sdk_url' => 'https://connect.facebook.net/en_US/sdk.js',
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect_url' => env('GOOGLE_REDIRECT_URL', env('APP_URL') . '/auth/google/callback'),
    ],

    'twitter' => [
        'api_key' => env('TWITTER_API_KEY'),
        'api_secret' => env('TWITTER_API_SECRET'),
    ],

];
