<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendNewsletterEmail;
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
        $query = News::with(['category', 'author', 'tags']);

        if (auth()->user()->hasRole('reporter')) {
            $query->where('author_id', auth()->id());
        }

        $news = $query->latest()->paginate(15);

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
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:news',
                'slug' => 'nullable|string|unique:news',
                'category_id' => 'required|exists:categories,id',
                'content' => 'required|string',
                'excerpt' => 'nullable|string|max:500',
                'featured_image' => 'nullable|image|max:1024',
                'status' => 'required|in:draft,published,scheduled',
                'published_at' => 'required_if:status,published,scheduled|date|nullable',
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

            // Reporters can only save as draft (needs approval)
            if (auth()->user()->hasRole('reporter')) {
                $validated['status'] = 'draft';
                $validated['published_at'] = null;
            }

            // Set published_at to now if status is published and date not set
            if ($validated['status'] === 'published' && empty($validated['published_at'])) {
                $validated['published_at'] = now();
            }

            // Create news post
            $news = auth()->user()->newsArticles()->create($validated);

            // Attach tags and auto-populate meta_keywords
            if (!empty($validated['tags'])) {
                $tags = array_map('trim', explode(',', $validated['tags']));
                $tagNames = collect($tags)->filter();
                $tagIds = $tagNames->map(fn($name) => \App\Models\Tag::firstOrCreate(
                    ['slug' => \Str::slug($name)],
                    ['name' => $name]
                ))->pluck('id');
                $news->tags()->sync($tagIds);
                // Auto-fill meta_keywords from topics if not manually set
                if (empty($news->meta_keywords)) {
                    $news->update(['meta_keywords' => $tagNames->implode(', ')]);
                }
            }

            // Log the activity
            $this->logActivity('created', 'News', $news->id, [
                'title' => $news->title,
                'category' => $news->category->name ?? 'N/A',
                'status' => $news->status,
            ]);

            // Send newsletter emails to all verified subscribers
            if ($news->status === 'published') {
                SendNewsletterEmail::dispatch($news->id)->delay(now()->addSeconds(10));
            }

            return redirect()
                ->route('admin.news.edit', $news)
                ->with('success', 'নিউজ সফলভাবে প্রকাশ করা হয়েছে! (News post created successfully!) - ' . $news->title);
        } catch (\Exception $e) {
            \Log::error('News creation error: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'নিউজ প্রকাশ করতে ত্রুটি হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a news post.
     */
    public function edit(News $news)
    {
        if (auth()->user()->hasRole('reporter') && $news->author_id !== auth()->id()) {
            abort(403, 'আপনি শুধুমাত্র নিজের সংবাদ সম্পাদনা করতে পারবেন।');
        }

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
        if (auth()->user()->hasRole('reporter') && $news->author_id !== auth()->id()) {
            abort(403, 'আপনি শুধুমাত্র নিজের সংবাদ সম্পাদনা করতে পারবেন।');
        }

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:news,title,' . $news->id,
                'slug' => 'nullable|string|unique:news,slug,' . $news->id,
                'category_id' => 'required|exists:categories,id',
                'content' => 'required|string',
                'excerpt' => 'nullable|string|max:500',
                'featured_image' => 'nullable|image|max:1024',
                'status' => 'required|in:draft,published,scheduled',
                'published_at' => 'required_if:status,published,scheduled|date|nullable',
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

            // Set published_at to now if status is published and date not set
            if ($validated['status'] === 'published' && empty($validated['published_at'])) {
                $validated['published_at'] = now();
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

            // Reporters cannot publish — force draft
            if (auth()->user()->hasRole('reporter')) {
                $validated['status'] = 'draft';
                $validated['published_at'] = null;
            }

            // Store old data for comparison
            $oldData = $news->getOriginal();

            // Update news post
            $news->update($validated);

            // Update tags and auto-sync meta_keywords
            if (isset($validated['tags'])) {
                $tags = array_map('trim', explode(',', $validated['tags']));
                $tagNames = collect($tags)->filter();
                $tagIds = $tagNames->map(fn($name) => \App\Models\Tag::firstOrCreate(
                    ['slug' => \Str::slug($name)],
                    ['name' => $name]
                ))->pluck('id');
                $news->tags()->sync($tagIds);
                // Always keep meta_keywords in sync with topics
                $news->update(['meta_keywords' => $tagNames->implode(', ')]);
            }

            // Log the activity with changes
            $changes = $this->getChanges($oldData, $news->fresh()->toArray(), ['updated_at', 'created_at']);
            $this->logActivity('updated', 'News', $news->id, $changes);

            // Send newsletter if newly published (was draft/scheduled, now published)
            $wasPublished = in_array($oldData['status'] ?? '', ['draft', 'scheduled']);
            if ($wasPublished && $news->status === 'published') {
                SendNewsletterEmail::dispatch($news->id)->delay(now()->addSeconds(10));
            }

            return redirect()
                ->route('admin.news.edit', $news)
                ->with('success', 'নিউজ সফলভাবে আপডেট করা হয়েছে! (News post updated successfully!)');
        } catch (\Exception $e) {
            \Log::error('News update error: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'নিউজ আপডেট করতে ত্রুটি হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Delete the specified news post.
     */
    public function destroy(News $news)
    {
        if (auth()->user()->hasRole('reporter')) {
            abort(403, 'রিপোর্টার সংবাদ মুছতে পারবেন না।');
        }

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
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:1024',
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
