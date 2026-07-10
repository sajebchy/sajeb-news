<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class JobPost extends Model
{
    use HasSlug, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'company_name', 'company_logo',
        'description', 'responsibilities', 'requirements', 'benefits',
        'job_sector', 'job_type', 'workplace_type',
        'experience_level', 'experience_min', 'experience_max',
        'salary_min', 'salary_max', 'salary_currency', 'salary_period', 'is_salary_negotiable',
        'location', 'district', 'division', 'country', 'latitude', 'longitude',
        'vacancy_count', 'education', 'skills',
        'application_url', 'application_email', 'application_deadline',
        'published_at', 'status', 'is_featured', 'is_urgent', 'views',
        'author_id',
        'meta_title', 'meta_description', 'meta_keywords',
        'canonical_url', 'og_description', 'og_image',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_urgent' => 'boolean',
        'is_salary_negotiable' => 'boolean',
        'published_at' => 'datetime',
        'application_deadline' => 'date',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeActive($query)
    {
        return $query->published()->where(function ($q) {
            $q->whereNull('application_deadline')
              ->orWhere('application_deadline', '>=', now()->toDateString());
        });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->published();
    }

    public function isExpired(): bool
    {
        return $this->application_deadline && $this->application_deadline->isPast();
    }

    public function getSalaryRangeAttribute(): string
    {
        if (!$this->salary_min && !$this->salary_max) {
            return $this->is_salary_negotiable ? 'আলোচনা সাপেক্ষে' : 'উল্লেখ নেই';
        }

        $format = fn($v) => number_format($v);
        $period = match ($this->salary_period) {
            'monthly' => '/মাস',
            'yearly' => '/বছর',
            default => '',
        };

        if ($this->salary_min && $this->salary_max) {
            return '৳' . $format($this->salary_min) . ' - ৳' . $format($this->salary_max) . $period;
        }

        if ($this->salary_min) {
            return '৳' . $format($this->salary_min) . '+ ' . $period;
        }

        return '৳' . $format($this->salary_max) . ' পর্যন্ত' . $period;
    }

    public function getJobTypeLabelAttribute(): string
    {
        return match ($this->job_type) {
            'full-time' => 'পূর্ণকালীন',
            'part-time' => 'খণ্ডকালীন',
            'contract' => 'চুক্তিভিত্তিক',
            'internship' => 'ইন্টার্নশিপ',
            'freelance' => 'ফ্রিল্যান্স',
            default => $this->job_type,
        };
    }

    public function getWorkplaceTypeLabelAttribute(): string
    {
        return match ($this->workplace_type) {
            'onsite' => 'অফিসে',
            'remote' => 'রিমোট',
            'hybrid' => 'হাইব্রিড',
            default => $this->workplace_type,
        };
    }

    public function getExperienceLabelAttribute(): string
    {
        if ($this->experience_min && $this->experience_max) {
            return $this->experience_min . '-' . $this->experience_max . ' বছর';
        }
        if ($this->experience_min) {
            return 'সর্বনিম্ন ' . $this->experience_min . ' বছর';
        }
        if ($this->experience_max) {
            return 'সর্বোচ্চ ' . $this->experience_max . ' বছর';
        }
        return match ($this->experience_level) {
            'entry' => 'প্রবেশ পর্যায়',
            'mid' => 'মধ্যম পর্যায়',
            'senior' => 'সিনিয়র',
            'lead' => 'লিড/ম্যানেজার',
            default => 'অভিজ্ঞতা আবশ্যক নয়',
        };
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function jobSectors(): array
    {
        return [
            'সরকারি চাকরি',
            'বেসরকারি চাকরি',
            'ব্যাংক ও আর্থিক প্রতিষ্ঠান',
            'এনজিও',
            'শিক্ষা প্রতিষ্ঠান',
            'আইটি ও টেলিকম',
            'স্বাস্থ্য ও চিকিৎসা',
            'প্রকৌশল',
            'মিডিয়া ও সাংবাদিকতা',
            'বিপণন ও বিক্রয়',
            'উৎপাদন ও শিল্প',
            'পরিবহন ও লজিস্টিক',
            'আইন ও বিচার',
            'প্রতিরক্ষা',
            'অন্যান্য',
        ];
    }

    public static function divisions(): array
    {
        return ['ঢাকা', 'চট্টগ্রাম', 'রাজশাহী', 'খুলনা', 'বরিশাল', 'সিলেট', 'রংপুর', 'ময়মনসিংহ'];
    }
}
