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
        Schema::create('live_streams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->string('status')->default('draft'); // draft, pending, live, ended, archived
            $table->string('thumbnail')->nullable();
            $table->string('stream_key')->unique(); // RTMP key for broadcasting
            $table->string('stream_url')->nullable(); // RTMP server URL
            $table->enum('visibility', ['public', 'private', 'unlisted'])->default('public');
            $table->integer('viewer_count')->default(0);
            $table->integer('peak_viewers')->default(0);
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->text('stream_tags')->nullable(); // JSON array
            $table->string('category')->nullable();
            $table->boolean('allow_comments')->default(true);
            $table->boolean('allow_chat')->default(true);
            $table->string('recording_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('view_count')->default(0);
            $table->integer('like_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('user_id');
            $table->index('started_at');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_streams');
    }
};
