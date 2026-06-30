<?php

return [
    /*
    |--------------------------------------------------------------------------
    | RTMP Server Configuration for Live Streaming
    |--------------------------------------------------------------------------
    |
    | Configure your RTMP server details for live video streaming using OBS
    | or other broadcasting software.
    |
    */

    'rtmp' => [
        // RTMP Server URL (e.g., rtmp://your-server.com)
        'server_url' => env('RTMP_SERVER_URL', 'rtmp://localhost'),

        // Application name on RTMP server
        'app_name' => env('RTMP_APP_NAME', 'live'),

        // RTMP server port
        'port' => env('RTMP_PORT', 1935),

        // HTTP-FLV port for viewing (if available)
        'http_flv_port' => env('HTTP_FLV_PORT', 8081),

        // HLS port for mobile viewing
        'hls_port' => env('HLS_PORT', 8082),
    ],

    /*
    |--------------------------------------------------------------------------
    | Live Stream Settings
    |--------------------------------------------------------------------------
    */

    'live_stream' => [
        // Maximum concurrent streams allowed
        'max_concurrent_streams' => env('MAX_CONCURRENT_STREAMS', 5),

        // Maximum stream duration in minutes (0 = unlimited)
        'max_duration_minutes' => env('MAX_STREAM_DURATION', 480),

        // Recording directory
        'recording_path' => storage_path('app/recordings'),

        // Thumbnail directory
        'thumbnail_path' => storage_path('app/thumbnails'),

        // Enable automatic recording
        'auto_record' => env('AUTO_RECORD_STREAM', true),

        // Enable chat during stream
        'enable_chat' => env('ENABLE_LIVE_CHAT', true),

        // Enable comments after stream
        'enable_comments' => env('ENABLE_STREAM_COMMENTS', true),
    ],
];
