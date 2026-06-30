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
        Schema::table('categories', function (Blueprint $table) {
            // Add ClaimReview specific fields
            if (!Schema::hasColumn('categories', 'is_fact_checker')) {
                $table->boolean('is_fact_checker')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('categories', 'claim_review_enabled')) {
                $table->boolean('claim_review_enabled')->default(false)->after('is_fact_checker');
            }
            if (!Schema::hasColumn('categories', 'claim_rating_scale')) {
                $table->enum('claim_rating_scale', ['True', 'Mostly True', 'Partly False', 'False', 'Unproven'])->nullable()->after('claim_review_enabled');
            }
            if (!Schema::hasColumn('categories', 'claim_reviewer_name')) {
                $table->string('claim_reviewer_name')->nullable()->after('claim_rating_scale');
            }
            if (!Schema::hasColumn('categories', 'claim_reviewer_url')) {
                $table->string('claim_reviewer_url')->nullable()->after('claim_reviewer_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $columns = ['is_fact_checker', 'claim_review_enabled', 'claim_rating_scale', 'claim_reviewer_name', 'claim_reviewer_url'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('categories', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
