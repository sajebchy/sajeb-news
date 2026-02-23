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
            // Feedify Configuration
            $table->boolean('feedify_enabled')->default(false)->after('show_between_articles_ads');
            $table->string('feedify_api_key')->nullable()->after('feedify_enabled');
            $table->string('feedify_list_id')->nullable()->after('feedify_api_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            $table->dropColumn(['feedify_enabled', 'feedify_api_key', 'feedify_list_id']);
        });
    }
};
