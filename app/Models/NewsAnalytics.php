<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsAnalytics extends Model
{
    protected $fillable = [
        'news_id',
        'daily_views',
        'total_views',
        'scroll_depth',
        'average_time_on_page',
        'bounce_rate',
        'social_shares',
        'comments_count',
        'date'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function news()
    {
        return $this->belongsTo(News::class);
    }

    public function getEngagementRateAttribute()
    {
        if ($this->daily_views == 0) {
            return 0;
        }
        $engagement = ($this->social_shares + $this->comments_count) / $this->daily_views;
        return round($engagement * 100, 2);
    }
}
