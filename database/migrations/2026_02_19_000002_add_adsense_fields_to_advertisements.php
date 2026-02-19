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
            if (!Schema::hasColumn('advertisements', 'ad_type')) {
                $table->enum('ad_type', ['standard', 'image', 'video', 'adsense'])->default('standard')->after('type');
            }
            if (!Schema::hasColumn('advertisements', 'adsense_code')) {
                $table->text('adsense_code')->nullable()->after('ad_type');
            }
            if (!Schema::hasColumn('advertisements', 'adsense_slot_id')) {
                $table->string('adsense_slot_id')->nullable()->after('adsense_code');
            }
            if (!Schema::hasColumn('advertisements', 'adsense_publisher_id')) {
                $table->string('adsense_publisher_id')->nullable()->after('adsense_slot_id');
            }
            if (!Schema::hasColumn('advertisements', 'is_adsense_enabled')) {
                $table->boolean('is_adsense_enabled')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('advertisements', 'disable_page_limit')) {
                $table->integer('disable_page_limit')->default(3)->after('is_adsense_enabled')->comment('Max ads per page as per AdSense policy');
            }
            if (!Schema::hasColumn('advertisements', 'minimum_content_length')) {
                $table->integer('minimum_content_length')->default(300)->after('disable_page_limit')->comment('Minimum words for AdSense policy');
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
                'ad_type',
                'adsense_code',
                'adsense_slot_id',
                'adsense_publisher_id',
                'is_adsense_enabled',
                'disable_page_limit',
                'minimum_content_length'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('advertisements', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
