<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            // Homepage hero slot, one news per slot:
            // 1 = big lead card (left); 2..5 stack down the right column and are
            // shown to editors as "Position 2-1/2-2" and "Position 3-1/3-2".
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
