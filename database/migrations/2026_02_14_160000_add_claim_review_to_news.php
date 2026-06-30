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
        Schema::table('news', function (Blueprint $table) {
            // Add ClaimReview specific fields
            if (!Schema::hasColumn('news', 'is_claim_review')) {
                $table->boolean('is_claim_review')->default(false)->after('is_featured');
            }
            if (!Schema::hasColumn('news', 'claim_being_reviewed')) {
                $table->text('claim_being_reviewed')->nullable()->after('is_claim_review');
            }
            if (!Schema::hasColumn('news', 'claim_rating')) {
                $table->enum('claim_rating', ['True', 'Mostly True', 'Partly False', 'False', 'Unproven'])->nullable()->after('claim_being_reviewed');
            }
            if (!Schema::hasColumn('news', 'claim_review_evidence')) {
                $table->longText('claim_review_evidence')->nullable()->after('claim_rating');
            }
            if (!Schema::hasColumn('news', 'claim_review_date')) {
                $table->timestamp('claim_review_date')->nullable()->after('claim_review_evidence');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $columns = ['is_claim_review', 'claim_being_reviewed', 'claim_rating', 'claim_review_evidence', 'claim_review_date'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('news', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
