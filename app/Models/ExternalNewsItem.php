<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalNewsItem extends Model
{
    protected $fillable = [
        'news_source_id',
        'guid',
        'url',
        'title',
        'author',
        'excerpt',
        'content',
        'image_url',
        'keywords',
        'published_at',
        'fetched_at',
        'is_read',
        'imported_news_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'fetched_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    public function source()
    {
        return $this->belongsTo(NewsSource::class, 'news_source_id');
    }

    /**
     * Keywords split into a trimmed, non-empty array.
     */
    public function getKeywordsArrayAttribute(): array
    {
        if (empty($this->keywords)) {
            return [];
        }

        return collect(explode(',', $this->keywords))
            ->map(fn ($k) => trim($k))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
