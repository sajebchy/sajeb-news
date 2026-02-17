<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    protected $fillable = [
        'endpoint',
        'public_key',
        'auth_token',
        'user_ip',
        'user_agent',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get active subscriptions only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Mark subscription as inactive
     */
    public function deactivate()
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Mark subscription as active
     */
    public function activate()
    {
        $this->update(['is_active' => true]);
    }
}
