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
        Schema::create('news_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('news_id');
            $table->integer('daily_views')->default(0);
            $table->integer('total_views')->default(0);
            $table->integer('scroll_depth')->default(0);
            $table->integer('average_time_on_page')->default(0);
            $table->integer('bounce_rate')->default(0);
            $table->integer('social_shares')->default(0);
            $table->integer('comments_count')->default(0);
            $table->date('date');
            $table->timestamps();
            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
            $table->index(['news_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_analytics');
    }
};
