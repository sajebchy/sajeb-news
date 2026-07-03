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
            $table->string('photo_card_logo')->nullable()->after('default_featured_image');
        });
    }

    public function down(): void
    {
        Schema::table('seo_settings', function (Blueprint $table) {
            $table->dropColumn('photo_card_logo');
        });
    }
};
