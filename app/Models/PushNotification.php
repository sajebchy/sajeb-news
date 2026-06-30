<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushNotification extends Model
{
    protected $fillable = [
        'title',
        'body',
        'image',
        'icon',
        'action_url',
        'target_audience',
        'segments',
        'scheduled_at',
        'is_sent',
        'created_by'
    ];

    protected $casts = [
        'is_sent' => 'boolean',
        'scheduled_at' => 'datetime',
        'segments' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getClickRateAttribute()
    {
        if ($this->sent_count == 0) {
            return 0;
        }
        return ($this->click_count / $this->sent_count) * 100;
    }
}
