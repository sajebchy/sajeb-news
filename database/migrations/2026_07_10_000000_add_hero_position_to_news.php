<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            // Hero section placement on homepage:
            // 1 = big lead (left), 2 = top-right pair, 3 = bottom-right pair.
            // NULL = not manually placed (auto-filled with latest news).
            $table->unsignedTinyInteger('hero_position')->nullable()->after('is_breaking');
            $table->index('hero_position');
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropIndex(['hero_position']);
            $table->dropColumn('hero_position');
        });
    }
};
