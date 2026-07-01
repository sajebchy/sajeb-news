<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = ['question', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function options()
    {
        return $this->hasMany(PollOption::class);
    }

    public static function getActive()
    {
        return static::where('is_active', true)->with('options')->latest()->first();
    }
}
