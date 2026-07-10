<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('company_name');
            $table->string('company_logo')->nullable();
            $table->text('description');
            $table->text('responsibilities')->nullable();
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();
            $table->string('job_sector')->default('অন্যান্য');
            $table->string('job_type')->default('full-time');
            $table->string('workplace_type')->default('onsite');
            $table->string('experience_level')->nullable();
            $table->unsignedTinyInteger('experience_min')->nullable();
            $table->unsignedTinyInteger('experience_max')->nullable();
            $table->unsignedInteger('salary_min')->nullable();
            $table->unsignedInteger('salary_max')->nullable();
            $table->string('salary_currency', 10)->default('BDT');
            $table->string('salary_period', 20)->default('monthly');
            $table->boolean('is_salary_negotiable')->default(false);
            $table->string('location')->nullable();
            $table->string('district')->nullable();
            $table->string('division')->nullable();
            $table->string('country')->default('বাংলাদেশ');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedSmallInteger('vacancy_count')->nullable();
            $table->string('education')->nullable();
            $table->text('skills')->nullable();
            $table->string('application_url')->nullable();
            $table->string('application_email')->nullable();
            $table->date('application_deadline')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_urgent')->default(false);
            $table->unsignedInteger('views')->default(0);
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('job_sector');
            $table->index('job_type');
            $table->index('district');
            $table->index('division');
            $table->index('is_featured');
            $table->index('application_deadline');
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
