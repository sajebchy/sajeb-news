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
        Schema::table('advertisements', function (Blueprint $table) {
            // Ad Placement Types
            $table->enum('placement', [
                'within_news',           // Within news article content
                'homepage_banner',       // Homepage banner
                'homepage_popup',        // Homepage popup
                'homepage_header',       // Homepage header
                'homepage_footer',       // Homepage footer
                'category_page',         // Category page
                'sidebar',               // Sidebar
                'between_comments'       // Between comments
            ])->after('type')->default('sidebar');

            // Image and URL
            $table->string('image_url')->nullable()->after('placement');
            $table->string('ad_url')->nullable()->after('image_url');
            $table->string('alt_text')->nullable()->after('ad_url');

            // UTM Parameters
            $table->string('utm_source')->nullable()->after('alt_text');
            $table->string('utm_medium')->nullable()->after('utm_source');
            $table->string('utm_campaign')->nullable()->after('utm_medium');
            $table->string('utm_term')->nullable()->after('utm_campaign');
            $table->string('utm_content')->nullable()->after('utm_term');

            // Performance Tracking
            $table->integer('views')->default(0)->after('utm_content');
            $table->integer('clicks')->default(0)->after('views');

            // Targeting Fields
            $table->json('target_categories')->nullable()->after('clicks');
            $table->json('target_tags')->nullable()->after('target_categories');

            // Display Settings
            $table->integer('display_order')->default(0)->after('target_tags');
            $table->boolean('show_on_mobile')->default(true)->after('display_order');
            $table->boolean('show_on_desktop')->default(true)->after('show_on_mobile');

            // Constraints
            $table->integer('daily_impression_limit')->nullable()->after('show_on_desktop');
            $table->integer('max_clicks_per_day')->nullable()->after('daily_impression_limit');

            // CPC/CPM Settings
            $table->decimal('cpc_amount', 10, 2)->nullable()->after('max_clicks_per_day')->comment('Cost per click');
            $table->decimal('cpm_amount', 10, 2)->nullable()->after('cpc_amount')->comment('Cost per thousand impressions');
            $table->decimal('total_spent', 10, 2)->default(0)->after('cpm_amount');

            // Advertiser Info
            $table->string('advertiser_name')->nullable()->after('total_spent');
            $table->string('advertiser_email')->nullable()->after('advertiser_name');
            $table->string('advertiser_phone')->nullable()->after('advertiser_email');

            // Status and Notes
            $table->text('notes')->nullable()->after('advertiser_phone');
            $table->index('placement');
            $table->index('display_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn([
                'placement',
                'image_url',
                'ad_url',
                'alt_text',
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_term',
                'utm_content',
                'views',
                'target_categories',
                'target_tags',
                'display_order',
                'show_on_mobile',
                'show_on_desktop',
                'daily_impression_limit',
                'max_clicks_per_day',
                'cpc_amount',
                'cpm_amount',
                'total_spent',
                'advertiser_name',
                'advertiser_email',
                'advertiser_phone',
                'notes'
            ]);
        });
    }
};
