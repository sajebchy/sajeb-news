<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations - Clean up SVG placeholder data from advertisements
     */
    public function up(): void
    {
        // Clear all SVG placeholders from image_url field
        DB::table('advertisements')
            ->where('image_url', 'like', 'data:image/svg%')
            ->update(['image_url' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reversal needed - this is data cleanup
    }
};
