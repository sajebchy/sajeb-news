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
            // Google AdSense Configuration
            $table->string('adsense_publisher_id')->nullable()->after('site_title');
            $table->longText('adsense_anchor_ad_code')->nullable()->after('adsense_publisher_id');
            $table->longText('adsense_sidebar_ad_code')->nullable()->after('adsense_anchor_ad_code');
            $table->longText('adsense_between_articles_ad_code')->nullable()->after('adsense_sidebar_ad_code');
            $table->boolean('show_anchor_ads')->default(true)->after('adsense_between_articles_ad_code');
            $table->boolean('show_sidebar_ads')->default(true)->after('show_anchor_ads');
            $table->boolean('show_between_articles_ads')->default(true)->after('show_sidebar_ads');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            $table->dropColumn([
                'adsense_publisher_id',
                'adsense_anchor_ad_code',
                'adsense_sidebar_ad_code',
                'adsense_between_articles_ad_code',
                'show_anchor_ads',
                'show_sidebar_ads',
                'show_between_articles_ads',
            ]);
        });
    }
};
