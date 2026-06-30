<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'news_id',
        'user_id',
        'facebook_user_id',
        'comment_text',
        'approved',
        'spam_score',
        'recaptcha_score',
    ];

    protected $casts = [
        'approved' => 'boolean',
        'spam_score' => 'decimal:2',
        'recaptcha_score' => 'decimal:2',
    ];

    /**
     * Get the news that owns the comment
     */
    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }

    /**
     * Get the user that made the comment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get approved comments
     */
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    /**
     * Scope to get pending comments
     */
    public function scopePending($query)
    {
        return $query->where('approved', false);
    }

    /**
     * Scope to get spam comments
     */
    public function scopeSpam($query)
    {
        return $query->where('spam_score', '>', 70);
    }
}
