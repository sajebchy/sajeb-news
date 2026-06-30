<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorAnalytic extends Model
{
    protected $table = 'visitor_analytics';

    protected $fillable = [
        'news_id',
        'visitor_ip',
        'visitor_country',
        'visitor_city',
        'visitor_device',
        'referrer_source',
        'referrer_url',
        'time_spent_seconds',
        'scroll_percentage',
        'completed_reading',
        'browser',
        'os',
        'visit_date',
        'next_news_id',
        'exit_time',
        'exit_page',
        'user_agent',
        'language',
        'screen_resolution',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'exit_time' => 'datetime',
        'completed_reading' => 'boolean',
    ];

    public function news()
    {
        return $this->belongsTo(News::class);
    }

    public function nextNews()
    {
        return $this->belongsTo(News::class, 'next_news_id');
    }

    public function getReadingTimeAttribute()
    {
        $seconds = $this->time_spent_seconds;
        
        if ($seconds < 60) {
            return "{$seconds}s";
        } elseif ($seconds < 3600) {
            $minutes = floor($seconds / 60);
            $remainingSeconds = $seconds % 60;
            return "{$minutes}m {$remainingSeconds}s";
        } else {
            $hours = floor($seconds / 3600);
            $remainingMinutes = floor(($seconds % 3600) / 60);
            return "{$hours}h {$remainingMinutes}m";
        }
    }

    public function getSourceIconAttribute()
    {
        $icons = [
            'google' => 'bi-google',
            'facebook' => 'bi-facebook',
            'twitter' => 'bi-twitter',
            'linkedin' => 'bi-linkedin',
            'whatsapp' => 'bi-whatsapp',
            'bing' => 'bi-search',
            'chatgpt' => 'bi-chat-dots',
            'direct' => 'bi-link-45deg',
        ];

        return $icons[$this->referrer_source] ?? 'bi-globe';
    }
}
