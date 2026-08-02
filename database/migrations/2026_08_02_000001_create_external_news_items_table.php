<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_news_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_source_id')->constrained('news_sources')->cascadeOnDelete();
            $table->string('guid', 500);
            $table->text('url');
            $table->string('title', 500);
            $table->string('author')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->text('image_url')->nullable();
            $table->text('keywords')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('fetched_at')->nullable();
            $table->boolean('is_read')->default(false);
            $table->unsignedBigInteger('imported_news_id')->nullable(); // Phase 2 hook
            $table->timestamps();

            $table->unique(['news_source_id', 'guid'], 'ext_news_source_guid_unique');
            $table->index('published_at');
            $table->index('is_read');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_news_items');
    }
};
