<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
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
        $featured = $this->newsService->getFeaturedNews(5);
        $breaking = $this->newsService->getBreakingNews(3);
        $latest = $this->newsService->getPublishedNews(9);
        $trending = $this->newsService->getTrendingNews(7, 10);
        $seoSettings = \App\Models\SeoSetting::first();

        return view('public.index', compact('featured', 'breaking', 'latest', 'trending', 'seoSettings'));
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

        return view('public.news.show', compact('news', 'related', 'metaTags', 'schema'));
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

        return view('public.category', compact('category', 'news', 'metaTags'));
    }

    /**
     * Search news
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        abort_if(strlen($query) < 2, 422, 'Search query must be at least 2 characters');

        $news = $this->newsService->searchNews($query);

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
