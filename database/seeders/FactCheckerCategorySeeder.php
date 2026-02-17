<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FactCheckerCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if Fact Checker category already exists
        $existing = Category::where('slug', 'fact-checker')->first();
        
        if (!$existing) {
            Category::create([
                'name' => 'Fact Checker',
                'slug' => 'fact-checker',
                'description' => 'Fact-checking articles where we verify claims, debunk false information, and provide truth ratings for various news and statements.',
                'is_fact_checker' => true,
                'claim_review_enabled' => true,
                'claim_rating_scale' => 'True',
                'claim_reviewer_name' => 'Sajeb News Fact Check Team',
                'claim_reviewer_url' => url('/'),
                'is_active' => true,
            ]);

            echo "✅ Fact Checker category created successfully!\n";
        } else {
            echo "ℹ️ Fact Checker category already exists.\n";
        }
    }
}
