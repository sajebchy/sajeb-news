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
            $table->string('recaptcha_site_key')->nullable()->after('facebook_pixel_id');
            $table->string('recaptcha_secret_key')->nullable()->after('recaptcha_site_key');
            $table->decimal('recaptcha_threshold', 3, 1)->default(0.5)->after('recaptcha_secret_key');
            $table->boolean('recaptcha_enabled')->default(false)->after('recaptcha_threshold');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            $table->dropColumn(['recaptcha_site_key', 'recaptcha_secret_key', 'recaptcha_threshold', 'recaptcha_enabled']);
        });
    }
};
