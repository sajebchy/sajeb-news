<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('seo_settings', 'mail_host')) {
                $table->string('mail_host')->nullable()->after('analytics_body_code');
            }
            if (!Schema::hasColumn('seo_settings', 'mail_port')) {
                $table->string('mail_port', 10)->nullable()->after('mail_host');
            }
            if (!Schema::hasColumn('seo_settings', 'mail_username')) {
                $table->string('mail_username')->nullable()->after('mail_port');
            }
            if (!Schema::hasColumn('seo_settings', 'mail_password')) {
                $table->string('mail_password')->nullable()->after('mail_username');
            }
            if (!Schema::hasColumn('seo_settings', 'mail_encryption')) {
                $table->string('mail_encryption', 10)->nullable()->after('mail_password');
            }
            if (!Schema::hasColumn('seo_settings', 'mail_from_address')) {
                $table->string('mail_from_address')->nullable()->after('mail_encryption');
            }
            if (!Schema::hasColumn('seo_settings', 'mail_from_name')) {
                $table->string('mail_from_name')->nullable()->after('mail_from_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            $table->dropColumn([
                'mail_host', 'mail_port', 'mail_username', 'mail_password',
                'mail_encryption', 'mail_from_address', 'mail_from_name',
            ]);
        });
    }
};
