<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('seo_settings', 'analytics_head_code')) {
                $table->text('analytics_head_code')->nullable()->after('enable_analytics');
            }
            if (!Schema::hasColumn('seo_settings', 'analytics_body_code')) {
                $table->text('analytics_body_code')->nullable()->after('analytics_head_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            $table->dropColumn(['analytics_head_code', 'analytics_body_code']);
        });
    }
};
