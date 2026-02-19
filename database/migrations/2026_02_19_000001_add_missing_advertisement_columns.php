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
            // Add missing columns that don't already exist
            if (!Schema::hasColumn('advertisements', 'target_categories')) {
                $table->json('target_categories')->nullable()->after('views');
            }
            if (!Schema::hasColumn('advertisements', 'target_tags')) {
                $table->json('target_tags')->nullable()->after('target_categories');
            }
            if (!Schema::hasColumn('advertisements', 'display_order')) {
                $table->integer('display_order')->default(0)->after('target_tags');
            }
            if (!Schema::hasColumn('advertisements', 'show_on_mobile')) {
                $table->boolean('show_on_mobile')->default(true)->after('display_order');
            }
            if (!Schema::hasColumn('advertisements', 'show_on_desktop')) {
                $table->boolean('show_on_desktop')->default(true)->after('show_on_mobile');
            }
            if (!Schema::hasColumn('advertisements', 'daily_impression_limit')) {
                $table->integer('daily_impression_limit')->nullable()->after('show_on_desktop');
            }
            if (!Schema::hasColumn('advertisements', 'max_clicks_per_day')) {
                $table->integer('max_clicks_per_day')->nullable()->after('daily_impression_limit');
            }
            if (!Schema::hasColumn('advertisements', 'cpc_amount')) {
                $table->decimal('cpc_amount', 10, 2)->nullable()->after('max_clicks_per_day');
            }
            if (!Schema::hasColumn('advertisements', 'cpm_amount')) {
                $table->decimal('cpm_amount', 10, 2)->nullable()->after('cpc_amount');
            }
            if (!Schema::hasColumn('advertisements', 'total_spent')) {
                $table->decimal('total_spent', 10, 2)->default(0)->after('cpm_amount');
            }
            if (!Schema::hasColumn('advertisements', 'advertiser_name')) {
                $table->string('advertiser_name')->nullable()->after('total_spent');
            }
            if (!Schema::hasColumn('advertisements', 'advertiser_email')) {
                $table->string('advertiser_email')->nullable()->after('advertiser_name');
            }
            if (!Schema::hasColumn('advertisements', 'advertiser_phone')) {
                $table->string('advertiser_phone')->nullable()->after('advertiser_email');
            }
            if (!Schema::hasColumn('advertisements', 'notes')) {
                $table->text('notes')->nullable()->after('advertiser_phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $columns = [
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
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('advertisements', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
