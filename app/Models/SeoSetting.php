<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    protected $table = 'seo_settings';

    protected $fillable = [
        'site_title',
        'site_name',
        'site_url',
        'site_description',
        'site_keywords',
        'meta_keywords',
        'logo',
        'mobile_logo',
        'favicon',
        'og_image',
        'about_page_content',
        'twitter_handle',
        'google_analytics_id',
        'google_tag_manager_id',
        'ga_tracking_id',
        'gtm_tracking_id',
        'facebook_pixel_id',
        'robots_txt',
        'enable_sitemap',
        'enable_robots',
        'enable_analytics',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'youtube_url',
        'linkedin_url',
        'tiktok_url',
        'recaptcha_site_key',
        'recaptcha_secret_key',
        'recaptcha_threshold',
        'recaptcha_enabled',
        'adsense_publisher_id',
        'adsense_anchor_ad_code',
        'adsense_sidebar_ad_code',
        'adsense_between_articles_ad_code',
        'show_anchor_ads',
        'show_sidebar_ads',
        'show_between_articles_ads',
        'feedify_enabled',
        'feedify_api_key',
        'feedify_list_id',
        'vapid_public_key',
        'vapid_private_key',
        'push_notifications_enabled',
    ];

    protected $casts = [
        'enable_sitemap' => 'boolean',
        'enable_robots' => 'boolean',
        'recaptcha_enabled' => 'boolean',
        'show_anchor_ads' => 'boolean',
        'show_sidebar_ads' => 'boolean',
        'show_between_articles_ads' => 'boolean',
        'feedify_enabled' => 'boolean',
        'push_notifications_enabled' => 'boolean',
    ];

    public static function getInstance()
    {
        return self::first() ?? self::create([
            'site_title' => 'Sajeb NEWS Bangladesh',
            'site_description' => 'Bengali News Portal',
            'enable_sitemap' => true,
            'enable_robots' => true,
        ]);
    }
}
