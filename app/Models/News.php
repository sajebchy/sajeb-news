<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

class News extends Model
{
    use HasSlug, SoftDeletes, HasTags;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'category_id',
        'author_id',
        'status',
        'is_featured',
        'is_breaking',
        'published_at',
        'scheduled_at',
        'views',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_description',
        'og_image',
        'twitter_card',
        'reading_time',
        'is_claim_review',
        'claim_being_reviewed',
        'claim_rating',
        'claim_review_evidence',
        'claim_review_date',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_breaking' => 'boolean',
        'is_claim_review' => 'boolean',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'claim_review_date' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function analytics()
    {
        return $this->hasMany(NewsAnalytics::class);
    }

    public function visitorAnalytics()
    {
        return $this->hasMany(VisitorAnalytic::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->published();
    }

    public function scopeBreakingNews($query)
    {
        return $query->where('is_breaking', true)->published();
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')->where('scheduled_at', '<=', now());
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();
        
        // Register NewsObserver
        static::observe(\App\Observers\NewsObserver::class);
        
        static::creating(function ($news) {
            $news->reading_time = ceil(str_word_count(strip_tags($news->content)) / 200);
        });

        static::updating(function ($news) {
            if ($news->isDirty('content')) {
                $news->reading_time = ceil(str_word_count(strip_tags($news->content)) / 200);
            }
        });
    }
}
