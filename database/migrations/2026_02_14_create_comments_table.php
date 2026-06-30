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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('facebook_user_id')->nullable();
            $table->text('comment_text');
            $table->boolean('approved')->default(false);
            $table->decimal('spam_score', 5, 2)->default(0);
            $table->decimal('recaptcha_score', 3, 2)->nullable();
            $table->timestamps();

            $table->index('news_id');
            $table->index('user_id');
            $table->index('approved');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
