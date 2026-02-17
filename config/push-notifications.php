<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Web Push Notifications Configuration
    |--------------------------------------------------------------------------
    */

    'enabled' => env('VAPID_PUBLIC_KEY') ? true : false,

    'vapid' => [
        /**
         * VAPID Public Key
         * 
         * Generate using: php artisan vapid:generate
         * Can be set in:
         * 1. .env file (VAPID_PUBLIC_KEY)
         * 2. Admin Settings Panel (/admin/settings -> Push Notifications)
         */
        'public_key' => env('VAPID_PUBLIC_KEY', ''),

        /**
         * VAPID Private Key
         * 
         * KEEP THIS SECRET! Never share or expose this key.
         * Generate using: php artisan vapid:generate
         * Can be set in:
         * 1. .env file (VAPID_PRIVATE_KEY)
         * 2. Admin Settings Panel (/admin/settings -> Push Notifications)
         */
        'private_key' => env('VAPID_PRIVATE_KEY', ''),
    ],

    'database' => [
        /**
         * Table name for storing push subscriptions
         */
        'subscriptions_table' => 'push_subscriptions',
    ],

    /**
     * Service Worker Configuration
     */
    'service_worker' => [
        'path' => '/service-worker.js',
        'scope' => '/',
    ],

    /**
     * Notification default configuration
     */
    'notification' => [
        'title' => 'সাজেব নিউজ',
        'icon' => '/images/logo.png',
        'badge' => '/images/badge.png',
        'require_interaction' => false,
    ],
];
