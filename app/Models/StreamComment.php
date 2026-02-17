<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StreamComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'live_stream_id',
        'commenter_name',
        'commenter_email',
        'facebook_id',
        'facebook_profile_url',
        'commenter_avatar',
        'comment_text',
        'source',
        'is_verified',
        'is_pinned',
        'likes_count',
        'is_approved',
        'admin_notes',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_pinned' => 'boolean',
        'is_approved' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function stream()
    {
        return $this->belongsTo(LiveStream::class, 'live_stream_id');
    }

    /**
     * Scopes
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeFromFacebook($query)
    {
        return $query->where('source', 'facebook');
    }

    /**
     * Methods
     */
    public function approve()
    {
        return $this->update(['is_approved' => true]);
    }

    public function reject()
    {
        return $this->update(['is_approved' => false]);
    }

    public function pin()
    {
        return $this->update(['is_pinned' => true]);
    }

    public function unpin()
    {
        return $this->update(['is_pinned' => false]);
    }

    public function incrementLikes()
    {
        return $this->increment('likes_count');
    }

    public function decrementLikes()
    {
        if ($this->likes_count > 0) {
            return $this->decrement('likes_count');
        }
    }
}
