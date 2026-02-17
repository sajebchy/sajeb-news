<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchemaSetting extends Model
{
    protected $table = 'schema_settings';

    protected $fillable = [
        'enable_news_article_schema',
        'enable_organization_schema',
        'enable_website_schema',
        'enable_breadcrumb_schema',
        'enable_person_schema',
        'enable_image_object_schema',
        'enable_video_object_schema',
        'enable_live_blog_schema',
        'enable_faq_schema',
        'enable_job_posting_schema',
        'enable_event_schema',
        'enable_claim_review_schema',
        'organization_name',
        'organization_description',
        'contact_email',
        'contact_phone',
        'contact_type',
    ];

    protected $casts = [
        'enable_news_article_schema' => 'boolean',
        'enable_organization_schema' => 'boolean',
        'enable_website_schema' => 'boolean',
        'enable_breadcrumb_schema' => 'boolean',
        'enable_person_schema' => 'boolean',
        'enable_image_object_schema' => 'boolean',
        'enable_video_object_schema' => 'boolean',
        'enable_live_blog_schema' => 'boolean',
        'enable_faq_schema' => 'boolean',
        'enable_job_posting_schema' => 'boolean',
        'enable_event_schema' => 'boolean',
        'enable_claim_review_schema' => 'boolean',
    ];

    /**
     * Get the singleton instance of schema settings
     */
    public static function getInstance()
    {
        return self::first() ?? self::create([
            'enable_news_article_schema' => true,
            'enable_organization_schema' => true,
            'enable_website_schema' => true,
            'enable_breadcrumb_schema' => true,
            'enable_person_schema' => true,
            'enable_image_object_schema' => true,
            'enable_video_object_schema' => true,
        ]);
    }
}
