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
        Schema::table('visitor_analytics', function (Blueprint $table) {
            // Add fields to track visitor's next destination after reading
            $table->unsignedBigInteger('next_news_id')->nullable()->after('scroll_percentage');
            $table->timestamp('exit_time')->nullable()->after('visit_date');
            $table->string('exit_page')->nullable()->after('exit_time');
            $table->string('user_agent')->nullable()->after('exit_page');
            $table->string('language')->nullable()->after('user_agent');
            $table->string('screen_resolution')->nullable()->after('language');

            // Add indexes for better query performance
            $table->index('visitor_ip');
            $table->index('next_news_id');
            $table->foreign('next_news_id')->references('id')->on('news')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitor_analytics', function (Blueprint $table) {
            $table->dropForeign(['next_news_id']);
            $table->dropIndex(['visitor_ip']);
            $table->dropIndex(['next_news_id']);
            $table->dropColumn([
                'next_news_id',
                'exit_time',
                'exit_page',
                'user_agent',
                'language',
                'screen_resolution',
            ]);
        });
    }
};
