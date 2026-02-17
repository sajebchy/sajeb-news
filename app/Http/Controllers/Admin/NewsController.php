<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use App\Traits\LogsActivity;
use App\Services\ImageOptimizer;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    use LogsActivity;
    /**
     * Display a listing of news posts.
     */
    public function index()
    {
        $news = News::with(['category', 'author', 'tags'])
            ->latest()
            ->paginate(15);

        return view('admin.news.index', [
            'news' => $news,
        ]);
    }

    /**
     * Show the form for creating a new news post.
     */
    public function create()
    {
        $categories = Category::all();

        return view('admin.news.create', [
            'news' => new News(),
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created news post.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:news',
            'slug' => 'nullable|string|unique:news',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:5120',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'is_breaking' => 'boolean',
            'is_claim_review' => 'nullable|boolean',
            'claim_being_reviewed' => 'nullable|required_if:is_claim_review,1|string|max:1000',
            'claim_rating' => 'nullable|required_if:is_claim_review,1|in:True,Mostly True,Partly False,False,Unproven',
            'claim_review_evidence' => 'nullable|required_if:is_claim_review,1|string',
            'claim_review_date' => 'nullable|date',
            'tags' => 'nullable|string',
        ]);

        // Generate slug if not provided
        if (!$validated['slug']) {
            $validated['slug'] = \Str::slug($validated['title']);
        }

        // Handle featured image upload with optimization
        if ($request->hasFile('featured_image')) {
            $optimizer = new ImageOptimizer();
            $validated['featured_image'] = $optimizer->optimize(
                $request->file('featured_image'),
                'featured_image',
                'news'
            );
        }

        // Create news post
        $news = auth()->user()->newsArticles()->create($validated);

        // Attach tags
        if (!empty($validated['tags'])) {
            $tags = array_map('trim', explode(',', $validated['tags']));
            $news->attachTags($tags);
        }

        // Log the activity
        $this->logActivity('created', 'News', $news->id, [
            'title' => $news->title,
            'category' => $news->category->name ?? 'N/A',
            'status' => $news->status,
        ]);

        return redirect()
            ->route('admin.news.edit', $news)
            ->with('success', 'News post created successfully!');
    }

    /**
     * Show the form for editing a news post.
     */
    public function edit(News $news)
    {
        $categories = Category::all();

        return view('admin.news.edit', [
            'news' => $news,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified news post.
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:news,title,' . $news->id,
            'slug' => 'nullable|string|unique:news,slug,' . $news->id,
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:5120',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'is_breaking' => 'boolean',
            'is_claim_review' => 'nullable|boolean',
            'claim_being_reviewed' => 'nullable|required_if:is_claim_review,1|string|max:1000',
            'claim_rating' => 'nullable|required_if:is_claim_review,1|in:True,Mostly True,Partly False,False,Unproven',
            'claim_review_evidence' => 'nullable|required_if:is_claim_review,1|string',
            'claim_review_date' => 'nullable|date',
            'tags' => 'nullable|string',
        ]);

        // Generate slug if not provided
        if (!$validated['slug']) {
            $validated['slug'] = \Str::slug($validated['title']);
        }

        // Handle featured image upload with optimization
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($news->featured_image) {
                \Storage::disk('public')->delete($news->featured_image);
            }
            $optimizer = new ImageOptimizer();
            $validated['featured_image'] = $optimizer->optimize(
                $request->file('featured_image'),
                'featured_image',
                'news'
            );
        }

        // Store old data for comparison
        $oldData = $news->getOriginal();

        // Update news post
        $news->update($validated);

        // Update tags
        if (isset($validated['tags'])) {
            $tags = array_map('trim', explode(',', $validated['tags']));
            $news->syncTags($tags);
        }

        // Log the activity with changes
        $changes = $this->getChanges($oldData, $news->fresh()->toArray(), ['updated_at', 'created_at']);
        $this->logActivity('updated', 'News', $news->id, $changes);

        return redirect()
            ->route('admin.news.edit', $news)
            ->with('success', 'News post updated successfully!');
    }

    /**
     * Delete the specified news post.
     */
    public function destroy(News $news)
    {
        $newsId = $news->id;
        $newsTitle = $news->title;

        // Delete featured image
        if ($news->featured_image) {
            \Storage::disk('public')->delete($news->featured_image);
        }

        $news->delete();

        // Log the activity
        $this->logActivity('deleted', 'News', $newsId, [
            'title' => $newsTitle,
        ]);

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'News post deleted successfully!');
    }

    /**
     * Upload image from TinyMCE editor with optimization
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
        ]);

        try {
            if ($request->file('file')) {
                $optimizer = new ImageOptimizer();
                $path = $optimizer->optimize(
                    $request->file('file'),
                    'news_content',
                    'news/images'
                );

                return response()->json([
                    'location' => asset('storage/' . $path)
                ]);
            }

            return response()->json(['error' => 'No file uploaded'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
