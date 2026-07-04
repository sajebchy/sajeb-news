<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use App\Models\Advertisement;
use App\Services\NewsService;
use App\Services\SeoService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected NewsService $newsService;
    protected SeoService $seoService;

    public function __construct(NewsService $newsService, SeoService $seoService)
    {
        $this->newsService = $newsService;
        $this->seoService = $seoService;
    }

    /**
     * Display homepage
     */
    public function index()
    {
        $featured = $this->newsService->getFeaturedNews(8);
        $breaking = $this->newsService->getBreakingNews(5);
        $latest = $this->newsService->getPublishedNews(12);
        $trending = $this->newsService->getTrendingNews(7, 10);
        $seoSettings = \App\Models\SeoSetting::first();

        $allCategories = Category::where('is_active', true)
            ->withCount(['news' => fn($q) => $q->where('status', 'published')])
            ->orderByRaw("CASE WHEN featured_order IS NOT NULL THEN featured_order ELSE 999 END")
            ->orderBy('name')
            ->get()
            ->filter(fn($cat) => $cat->news_count > 0)
            ->each(function ($cat) {
                $cat->setRelation('latestNews', \App\Models\News::where('status', 'published')
                    ->where('category_id', $cat->id)
                    ->latest('published_at')
                    ->limit(8)
                    ->get());
            });

        return view('public.index', compact('featured', 'breaking', 'latest', 'trending', 'seoSettings', 'allCategories'));
    }

    /**
     * Display news detail
     */
    public function show(News $news)
    {
        abort_if($news->status !== 'published', 404);

        // Increment views
        $news->increment('views');

        $related = $this->newsService->getRelatedNews($news, 5);
        $metaTags = $this->seoService->getNewsMetaTags($news);
        $schema = $this->seoService->getNewsSchema($news);
        
        $adMiddle       = \App\Helpers\AdHelper::getRandomAdByPlacement('article_middle');
        $adConclusion   = \App\Helpers\AdHelper::getRandomAdByPlacement('article_conclusion');
        $adBelowArticle = \App\Helpers\AdHelper::getRandomAdByPlacement('below_article');

        return view('public.news.show-modern', compact('news', 'related', 'metaTags', 'schema', 'adMiddle', 'adConclusion', 'adBelowArticle'));
    }

    /**
     * Display news by category
     */
    public function category(Category $category)
    {
        $news = $this->newsService->getNewsByCategory($category);
        $metaTags = [
            'title' => $category->meta_title ?? $category->name . ' - Sajeb NEWS',
            'description' => $category->meta_description ?? $category->description,
            'keywords' => $category->meta_keywords,
        ];
        $poll = \App\Models\Poll::getActive();

        return view('public.category', compact('category', 'news', 'metaTags', 'poll'));
    }

    /**
     * Search news
     */
    public function search(Request $request)
    {
        // Make search query optional - allow visiting /search without any query
        $request->validate([
            'q' => 'nullable|string|max:255',
        ], [
            'q.max' => 'Search query cannot exceed 255 characters',
        ]);

        $query = trim($request->input('q', ''));
        $news = collect();
        
        // Only search if query is not empty
        if (!empty($query)) {
            $news = $this->newsService->searchNews($query);
        }

        return view('public.search', compact('query', 'news'));
    }

    /**
     * Display news by tag
     */
    public function tag($tag)
    {
        $news = $this->newsService->getNewsByTag($tag);

        return view('public.tag', compact('tag', 'news'));
    }

    /**
     * Display author profile
     */
    public function author($author)
    {
        $author = \App\Models\User::where('id', $author)->firstOrFail();
        $news = $author->newsArticles()
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(15);

        return view('public.author', compact('author', 'news'));
    }
}
