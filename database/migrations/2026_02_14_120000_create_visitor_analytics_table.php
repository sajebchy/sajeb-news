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
        Schema::create('visitor_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('news_id');
            $table->string('visitor_ip')->nullable();
            $table->string('visitor_country')->nullable();
            $table->string('visitor_city')->nullable();
            $table->string('visitor_device')->nullable();
            $table->string('referrer_source')->nullable(); // google, facebook, twitter, bing, chatgpt, direct, etc
            $table->string('referrer_url')->nullable();
            $table->integer('time_spent_seconds')->default(0); // seconds
            $table->integer('scroll_percentage')->default(0);
            $table->boolean('completed_reading')->default(false);
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->timestamp('visit_date')->useCurrent();
            $table->timestamps();

            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
            $table->index(['news_id', 'visit_date']);
            $table->index('referrer_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_analytics');
    }
};
