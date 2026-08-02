<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsSource extends Model
{
    protected $fillable = [
        'name',
        'site_url',
        'feed_url',
        'is_active',
        'last_fetched_at',
        'last_status',
        'last_error',
        'items_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_fetched_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(ExternalNewsItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
