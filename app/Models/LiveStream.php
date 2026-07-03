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
        'embed_url',
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
     * Get stream URL for viewing
     */
    public function getStreamUrl(): string
    {
        return route('live.watch', $this->slug);
    }

    /**
     * Convert the admin-provided embed link (YouTube / Facebook URL or full
     * <iframe> embed code) into a safe iframe `src` URL.
     *
     * Only YouTube and Facebook domains are allowed, so a pasted link cannot
     * be turned into an arbitrary/hostile iframe source. Returns null when the
     * link is empty or from an unsupported source.
     */
    public function getEmbedSrc(): ?string
    {
        $raw = trim((string) $this->embed_url);
        if ($raw === '') {
            return null;
        }

        // If a full <iframe ... src="..."> was pasted, pull out the src.
        if (stripos($raw, '<iframe') !== false
            && preg_match('/src\s*=\s*["\']([^"\']+)["\']/i', $raw, $m)) {
            $raw = html_entity_decode($m[1]);
        }

        // Normalize protocol-relative URLs.
        if (str_starts_with($raw, '//')) {
            $raw = 'https:' . $raw;
        }

        // --- YouTube ---------------------------------------------------------
        if (preg_match('~(?:youtube\.com|youtu\.be)~i', $raw)) {
            $videoId = null;

            if (preg_match('~youtu\.be/([A-Za-z0-9_-]{6,})~i', $raw, $m)) {
                $videoId = $m[1];
            } elseif (preg_match('~[?&]v=([A-Za-z0-9_-]{6,})~i', $raw, $m)) {
                $videoId = $m[1];
            } elseif (preg_match('~youtube\.com/(?:embed|live|shorts)/([A-Za-z0-9_-]{6,})~i', $raw, $m)) {
                $videoId = $m[1];
            }

            if ($videoId) {
                return 'https://www.youtube.com/embed/' . $videoId;
            }

            // Channel-level live URL (…/@handle/live or /channel/ID/live)
            if (preg_match('~youtube\.com/(channel/[A-Za-z0-9_-]+|@[A-Za-z0-9_.-]+)~i', $raw)) {
                return 'https://www.youtube.com/embed/live_stream?' . http_build_query([
                    'channel' => $this->extractYoutubeChannel($raw),
                ]);
            }

            return null;
        }

        // --- Facebook --------------------------------------------------------
        if (preg_match('~(?:facebook\.com|fb\.watch|fb\.me)~i', $raw)) {
            // Already a plugins embed URL → keep only if it is on facebook.com.
            if (stripos($raw, 'facebook.com/plugins/video.php') !== false) {
                return $raw;
            }

            return 'https://www.facebook.com/plugins/video.php?' . http_build_query([
                'href'      => $raw,
                'show_text' => 'false',
                'autoplay'  => 'true',
            ]);
        }

        return null;
    }

    /**
     * Detect which supported platform the embed link belongs to.
     */
    /**
     * Best preview image for admin/list cards:
     *  - YouTube: the real video thumbnail derived from the embed link
     *  - otherwise: the uploaded thumbnail (if any)
     *  - otherwise: null (caller shows a placeholder)
     */
    public function getPreviewImage(): ?string
    {
        $id = $this->getYoutubeId();
        if ($id) {
            return 'https://img.youtube.com/vi/' . $id . '/hqdefault.jpg';
        }

        if ($this->thumbnail) {
            return \Illuminate\Support\Str::startsWith($this->thumbnail, 'http')
                ? $this->thumbnail
                : asset('storage/' . $this->thumbnail);
        }

        return null;
    }

    /**
     * Extract the YouTube video id from the normalized embed src, if any.
     */
    public function getYoutubeId(): ?string
    {
        $src = $this->getEmbedSrc();
        if ($src && preg_match('~youtube\.com/embed/([A-Za-z0-9_-]{6,})~i', $src, $m)) {
            return $m[1];
        }
        return null;
    }

    public function getEmbedPlatform(): ?string
    {
        $raw = strtolower((string) $this->embed_url);
        if ($raw === '') {
            return null;
        }
        if (str_contains($raw, 'youtube') || str_contains($raw, 'youtu.be')) {
            return 'youtube';
        }
        if (str_contains($raw, 'facebook') || str_contains($raw, 'fb.watch') || str_contains($raw, 'fb.me')) {
            return 'facebook';
        }
        return null;
    }

    private function extractYoutubeChannel(string $url): ?string
    {
        if (preg_match('~youtube\.com/channel/([A-Za-z0-9_-]+)~i', $url, $m)) {
            return $m[1];
        }
        return null;
    }
}
