<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Live Stream Settings
    |--------------------------------------------------------------------------
    |
    | Live streaming is handled via external Facebook / YouTube embed links.
    | (The previous RTMP/OBS broadcasting system has been removed.)
    |
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
