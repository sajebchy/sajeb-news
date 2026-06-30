<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;

class Category extends Model
{
    use HasSlug, HasTags;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'icon',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'order',
        'featured_order',
        'is_active',
        'is_fact_checker',
        'claim_review_enabled',
        'claim_rating_scale',
        'claim_reviewer_name',
        'claim_reviewer_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_fact_checker' => 'boolean',
        'claim_review_enabled' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
