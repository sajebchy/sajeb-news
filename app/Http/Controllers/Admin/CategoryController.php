<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = Category::withCount('news')
            ->latest()
            ->paginate(20);

        return view('admin.categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $parents = Category::whereNull('parent_id')->get();

        return view('admin.categories.create', [
            'parents' => $parents,
        ]);
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'slug' => 'nullable|string|unique:categories',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'color' => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'icon' => 'nullable|string',
            'featured_order' => 'nullable|integer|min:1|max:5',
            'is_fact_checker' => 'nullable|boolean',
            'claim_review_enabled' => 'nullable|boolean',
            'claim_rating_scale' => 'nullable|in:True,Mostly True,Partly False,False,Unproven',
            'claim_reviewer_name' => 'nullable|string|max:255',
            'claim_reviewer_url' => 'nullable|url',
        ]);

        // Generate slug if not provided
        if (!$validated['slug']) {
            $validated['slug'] = \Str::slug($validated['name']);
        }

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Show the form for editing a category.
     */
    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)
            ->whereNull('parent_id')
            ->get();

        return view('admin.categories.edit', [
            'category' => $category,
            'parents' => $parents,
        ]);
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'color' => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'icon' => 'nullable|string',
            'featured_order' => 'nullable|integer|min:1|max:5|unique:categories,featured_order,' . $category->id,
            'is_fact_checker' => 'nullable|boolean',
            'claim_review_enabled' => 'nullable|boolean',
            'claim_rating_scale' => 'nullable|in:True,Mostly True,Partly False,False,Unproven',
            'claim_reviewer_name' => 'nullable|string|max:255',
            'claim_reviewer_url' => 'nullable|url',
        ]);

        // Generate slug if not provided
        if (!$validated['slug']) {
            $validated['slug'] = \Str::slug($validated['name']);
        }

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Delete the specified category.
     */
    public function destroy(Category $category)
    {
        // Check if category has news
        if ($category->news()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'Cannot delete category with news posts. Move or delete associated posts first.');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}
