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
        Schema::table('live_streams', function (Blueprint $table) {
            // Facebook / YouTube embed link (raw URL or full <iframe> embed code)
            $table->text('embed_url')->nullable()->after('stream_url');
        });

        // RTMP stream key is no longer required (embed-based streaming)
        Schema::table('live_streams', function (Blueprint $table) {
            $table->string('stream_key')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('live_streams', function (Blueprint $table) {
            $table->dropColumn('embed_url');
        });
    }
};
