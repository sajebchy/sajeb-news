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
            // Add ad_network column to support multiple ad networks
            $table->string('ad_network')
                ->nullable()
                ->after('ad_type')
                ->comment('Ad network: adsense, media_net, ezoic, propeller_ads, mediavine, raptive, monumetric, adsterra, monetag, infolinks, taboola_outbrain, amazon_associates');

            // Add network_config JSON column to store network-specific configuration
            $table->json('network_config')
                ->nullable()
                ->after('ad_network')
                ->comment('Network-specific configuration (code, zone_id, site_id, etc.)');

            // Add index for quick network lookups
            $table->index('ad_network');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropIndex(['ad_network']);
            $table->dropColumn(['ad_network', 'network_config']);
        });
    }
};
