<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class LiveStream extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'slug',
        'status',
        'thumbnail',
        'stream_key',
        'stream_url',
        'visibility',
        'viewer_count',
        'peak_viewers',
        'scheduled_at',
        'started_at',
        'ended_at',
        'duration_seconds',
        'stream_tags',
        'category',
        'allow_comments',
        'allow_chat',
        'recording_url',
        'is_featured',
        'view_count',
        'like_count',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'allow_comments' => 'boolean',
        'allow_chat' => 'boolean',
        'is_featured' => 'boolean',
        'stream_tags' => 'array',
    ];

    /**
     * Get the slug options.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the user that owns the live stream.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if stream is currently live
     */
    public function isLive(): bool
    {
        return $this->status === 'live' && $this->started_at && !$this->ended_at;
    }

    /**
     * Check if stream is scheduled
     */
    public function isScheduled(): bool
    {
        return $this->status === 'pending' && $this->scheduled_at > now();
    }

    /**
     * Check if stream has ended
     */
    public function hasEnded(): bool
    {
        return $this->status === 'ended' && $this->ended_at;
    }

    /**
     * Get stream duration in formatted time
     */
    public function getFormattedDuration(): string
    {
        $seconds = $this->duration_seconds;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
        }

        return sprintf('%02d:%02d', $minutes, $secs);
    }

    /**
     * Generate unique stream key
     */
    public static function generateStreamKey(): string
    {
        return strtolower(substr(md5(uniqid(mt_rand(), true)), 0, 32));
    }

    /**
     * Get the RTMP stream URL for OBS configuration
     */
    public function getRtmpUrl(): string
    {
        return config('services.rtmp.server_url') . '/' . config('services.rtmp.app_name');
    }

    /**
     * Get stream URL for viewing
     */
    public function getStreamUrl(): string
    {
        return route('live.watch', $this->slug);
    }
}
