<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('seo_settings', 'site_name')) {
                $table->string('site_name')->nullable()->after('site_title');
            }
            if (!Schema::hasColumn('seo_settings', 'site_url')) {
                $table->string('site_url')->nullable()->after('site_name');
            }
            if (!Schema::hasColumn('seo_settings', 'meta_keywords')) {
                $table->text('meta_keywords')->nullable()->after('site_keywords');
            }
            if (!Schema::hasColumn('seo_settings', 'mobile_logo')) {
                $table->string('mobile_logo')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('seo_settings', 'ga_tracking_id')) {
                $table->string('ga_tracking_id')->nullable()->after('google_analytics_id');
            }
            if (!Schema::hasColumn('seo_settings', 'gtm_tracking_id')) {
                $table->string('gtm_tracking_id')->nullable()->after('google_tag_manager_id');
            }
            if (!Schema::hasColumn('seo_settings', 'enable_analytics')) {
                $table->boolean('enable_analytics')->default(true)->after('enable_robots');
            }
            if (!Schema::hasColumn('seo_settings', 'facebook_url')) {
                $table->string('facebook_url')->nullable()->after('facebook_pixel_id');
            }
            if (!Schema::hasColumn('seo_settings', 'twitter_url')) {
                $table->string('twitter_url')->nullable()->after('facebook_url');
            }
            if (!Schema::hasColumn('seo_settings', 'instagram_url')) {
                $table->string('instagram_url')->nullable()->after('twitter_url');
            }
            if (!Schema::hasColumn('seo_settings', 'youtube_url')) {
                $table->string('youtube_url')->nullable()->after('instagram_url');
            }
            if (!Schema::hasColumn('seo_settings', 'linkedin_url')) {
                $table->string('linkedin_url')->nullable()->after('youtube_url');
            }
            if (!Schema::hasColumn('seo_settings', 'tiktok_url')) {
                $table->string('tiktok_url')->nullable()->after('linkedin_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            // Drop added columns
            $columns = ['site_name', 'site_url', 'meta_keywords', 'mobile_logo', 'ga_tracking_id', 'gtm_tracking_id', 'enable_analytics', 'facebook_url', 'twitter_url', 'instagram_url', 'youtube_url', 'linkedin_url', 'tiktok_url'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('seo_settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
