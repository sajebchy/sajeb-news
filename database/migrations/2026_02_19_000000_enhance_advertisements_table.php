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
        if (Schema::hasTable('advertisements')) {
            Schema::table('advertisements', function (Blueprint $table) {
                // Ad Placement Types
                if (!Schema::hasColumn('advertisements', 'placement')) {
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
                }

                // Image and URL
                if (!Schema::hasColumn('advertisements', 'image_url')) {
                    $table->string('image_url')->nullable()->after('placement');
                }
                if (!Schema::hasColumn('advertisements', 'ad_url')) {
                    $table->string('ad_url')->nullable()->after('image_url');
                }
                if (!Schema::hasColumn('advertisements', 'alt_text')) {
                    $table->string('alt_text')->nullable()->after('ad_url');
                }

                // UTM Parameters
                if (!Schema::hasColumn('advertisements', 'utm_source')) {
                    $table->string('utm_source')->nullable()->after('alt_text');
                }
                if (!Schema::hasColumn('advertisements', 'utm_medium')) {
                    $table->string('utm_medium')->nullable()->after('utm_source');
                }
                if (!Schema::hasColumn('advertisements', 'utm_campaign')) {
                    $table->string('utm_campaign')->nullable()->after('utm_medium');
                }
                if (!Schema::hasColumn('advertisements', 'utm_term')) {
                    $table->string('utm_term')->nullable()->after('utm_campaign');
                }
                if (!Schema::hasColumn('advertisements', 'utm_content')) {
                    $table->string('utm_content')->nullable()->after('utm_term');
                }

                // Performance Tracking
                if (!Schema::hasColumn('advertisements', 'views')) {
                    $table->integer('views')->default(0)->after('utm_content');
                }
                if (!Schema::hasColumn('advertisements', 'clicks')) {
                    $table->integer('clicks')->default(0)->after('views');
                }

                // Targeting Fields
                if (!Schema::hasColumn('advertisements', 'target_categories')) {
                    $table->json('target_categories')->nullable()->after('clicks');
                }
                if (!Schema::hasColumn('advertisements', 'target_tags')) {
                    $table->json('target_tags')->nullable()->after('target_categories');
                }

                // Display Settings
                if (!Schema::hasColumn('advertisements', 'display_order')) {
                    $table->integer('display_order')->default(0)->after('target_tags');
                }
                if (!Schema::hasColumn('advertisements', 'show_on_mobile')) {
                    $table->boolean('show_on_mobile')->default(true)->after('display_order');
                }
                if (!Schema::hasColumn('advertisements', 'show_on_desktop')) {
                    $table->boolean('show_on_desktop')->default(true)->after('show_on_mobile');
                }

                // Constraints
                if (!Schema::hasColumn('advertisements', 'daily_impression_limit')) {
                    $table->integer('daily_impression_limit')->nullable()->after('show_on_desktop');
                }
                if (!Schema::hasColumn('advertisements', 'max_clicks_per_day')) {
                    $table->integer('max_clicks_per_day')->nullable()->after('daily_impression_limit');
                }

                // CPC/CPM Settings
                if (!Schema::hasColumn('advertisements', 'cpc_amount')) {
                    $table->decimal('cpc_amount', 10, 2)->nullable()->after('max_clicks_per_day')->comment('Cost per click');
                }
                if (!Schema::hasColumn('advertisements', 'cpm_amount')) {
                    $table->decimal('cpm_amount', 10, 2)->nullable()->after('cpc_amount')->comment('Cost per thousand impressions');
                }
                if (!Schema::hasColumn('advertisements', 'total_spent')) {
                    $table->decimal('total_spent', 10, 2)->default(0)->after('cpm_amount');
                }

                // Advertiser Info
                if (!Schema::hasColumn('advertisements', 'advertiser_name')) {
                    $table->string('advertiser_name')->nullable()->after('total_spent');
                }
                if (!Schema::hasColumn('advertisements', 'advertiser_email')) {
                    $table->string('advertiser_email')->nullable()->after('advertiser_name');
                }
                if (!Schema::hasColumn('advertisements', 'advertiser_phone')) {
                    $table->string('advertiser_phone')->nullable()->after('advertiser_email');
                }

                // Status and Notes
                if (!Schema::hasColumn('advertisements', 'notes')) {
                    $table->text('notes')->nullable()->after('advertiser_phone');
                }
                
                // Add indices if they don't exist
                try {
                    $table->index('placement');
                } catch (\Exception $e) {}
                try {
                    $table->index('display_order');
                } catch (\Exception $e) {}
            });
        }
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
