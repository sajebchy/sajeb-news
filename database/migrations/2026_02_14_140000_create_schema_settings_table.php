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
        Schema::create('schema_settings', function (Blueprint $table) {
            $table->id();
            
            // NewsArticle settings
            $table->boolean('enable_news_article_schema')->default(true);
            
            // Organization settings
            $table->boolean('enable_organization_schema')->default(true);
            
            // Website settings
            $table->boolean('enable_website_schema')->default(true);
            
            // BreadcrumbList settings
            $table->boolean('enable_breadcrumb_schema')->default(true);
            
            // Person (Author) settings
            $table->boolean('enable_person_schema')->default(true);
            
            // ImageObject settings
            $table->boolean('enable_image_object_schema')->default(true);
            
            // VideoObject settings
            $table->boolean('enable_video_object_schema')->default(false);
            
            // LiveBlogPosting settings
            $table->boolean('enable_live_blog_schema')->default(false);
            
            // FAQPage settings
            $table->boolean('enable_faq_schema')->default(false);
            
            // JobPosting settings
            $table->boolean('enable_job_posting_schema')->default(false);
            
            // Event settings
            $table->boolean('enable_event_schema')->default(false);
            
            // ClaimReview settings
            $table->boolean('enable_claim_review_schema')->default(false);
            
            // General schema settings
            $table->string('organization_name')->nullable();
            $table->text('organization_description')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_type')->default('Customer Service');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schema_settings');
    }
};
