<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove the RTMP/OBS broadcasting columns (replaced by embed_url).
     */
    public function up(): void
    {
        Schema::table('live_streams', function (Blueprint $table) {
            // Drop the unique index on stream_key first (if present), then the columns.
            if (Schema::hasColumn('live_streams', 'stream_key')) {
                try {
                    $table->dropUnique('live_streams_stream_key_unique');
                } catch (\Throwable $e) {
                    // Index may not exist; ignore.
                }
                $table->dropColumn('stream_key');
            }
            if (Schema::hasColumn('live_streams', 'stream_url')) {
                $table->dropColumn('stream_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('live_streams', function (Blueprint $table) {
            $table->string('stream_key')->nullable()->after('thumbnail');
            $table->string('stream_url')->nullable()->after('stream_key');
        });
    }
};
