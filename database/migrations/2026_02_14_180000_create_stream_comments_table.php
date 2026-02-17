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
        Schema::create('stream_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_stream_id')->constrained('live_streams')->onDelete('cascade');
            $table->string('commenter_name');
            $table->string('commenter_email')->nullable();
            $table->string('facebook_id')->nullable()->unique();
            $table->string('facebook_profile_url')->nullable();
            $table->string('commenter_avatar')->nullable();
            $table->text('comment_text');
            $table->enum('source', ['facebook', 'website', 'anonymous'])->default('website');
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->integer('likes_count')->default(0);
            $table->boolean('is_approved')->default(true);
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('live_stream_id');
            $table->index('facebook_id');
            $table->index('created_at');
            $table->index('is_pinned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stream_comments');
    }
};
