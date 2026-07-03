<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Publisher / contact info shown in the site footer, editable from
     * Admin → Settings → Basic Settings.
     */
    public function up(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('seo_settings', 'editor_publisher')) {
                $table->string('editor_publisher')->nullable()->after('site_description');
            }
            if (!Schema::hasColumn('seo_settings', 'office_address')) {
                $table->string('office_address', 500)->nullable()->after('editor_publisher');
            }
            if (!Schema::hasColumn('seo_settings', 'office_mobile')) {
                $table->string('office_mobile', 100)->nullable()->after('office_address');
            }
            if (!Schema::hasColumn('seo_settings', 'office_email')) {
                $table->string('office_email', 191)->nullable()->after('office_mobile');
            }
        });
    }

    public function down(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            $table->dropColumn(['editor_publisher', 'office_address', 'office_mobile', 'office_email']);
        });
    }
};
