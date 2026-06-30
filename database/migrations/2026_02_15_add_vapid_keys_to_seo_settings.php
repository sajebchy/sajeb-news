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
            if (!Schema::hasColumn('seo_settings', 'vapid_public_key')) {
                $table->text('vapid_public_key')->nullable()->after('recaptcha_secret_key');
            }
            if (!Schema::hasColumn('seo_settings', 'vapid_private_key')) {
                $table->text('vapid_private_key')->nullable()->after('vapid_public_key');
            }
            if (!Schema::hasColumn('seo_settings', 'push_notifications_enabled')) {
                $table->boolean('push_notifications_enabled')->default(false)->after('vapid_private_key');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            if (Schema::hasColumn('seo_settings', 'vapid_public_key')) {
                $table->dropColumn('vapid_public_key');
            }
            if (Schema::hasColumn('seo_settings', 'vapid_private_key')) {
                $table->dropColumn('vapid_private_key');
            }
            if (Schema::hasColumn('seo_settings', 'push_notifications_enabled')) {
                $table->dropColumn('push_notifications_enabled');
            }
        });
    }
};
