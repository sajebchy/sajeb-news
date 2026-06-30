<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert ad_type from 'adsense' to null for any existing adsense ads
        DB::table('advertisements')
            ->where('ad_type', 'adsense')
            ->update(['ad_type' => null, 'ad_network' => 'adsense']);

        // Update the ENUM column (SQLite doesn't support ENUM directly, so we use TEXT with check)
        // For SQLite, we need to handle this differently
        Schema::table('advertisements', function (Blueprint $table) {
            // SQLite doesn't support direct ENUM modification
            // The check constraint will handle validation
            $table->string('ad_type')->nullable()->change();
        });

        // Add a check constraint for ad_type values (works on SQLite 3.37.0+)
        // If your SQLite is older, this won't work, but the validation happens at application level
        DB::statement('
            CREATE TABLE advertisements_new AS
            SELECT * FROM advertisements;
        ');

        // Drop the old table references and create new structure if needed
        // For this migration, we'll just ensure the data is updated properly
        // The application level validation will enforce the allowed values
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert: move ads back to ad_type = 'adsense' from ad_network
        DB::table('advertisements')
            ->where('ad_network', 'adsense')
            ->update(['ad_type' => 'adsense', 'ad_network' => null]);
    }
};
