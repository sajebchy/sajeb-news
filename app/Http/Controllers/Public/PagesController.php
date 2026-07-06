<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Category;
use App\Models\SeoSetting;
use App\Services\SeoService;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    protected SeoService $seoService;

    public function __construct(SeoService $seoService)
    {
        $this->seoService = $seoService;
    }

    /**
     * Display About page
     */
    public function about()
    {
        $seoSettings = SeoSetting::first();
        
        // Get about page content from settings, fallback to default if not set
        $aboutContent = $seoSettings->about_page_content ?? null;
        
        $metaTags = [
            'title' => 'আমাদের সম্পর্কে - Sajeb NEWS',
            'description' => 'Sajeb NEWS হল বাংলাদেশের একটি বিশ্বস্ত নিউজ পোর্টাল। আমরা সর্বশেষ এবং নির্ভরযোগ্য খবর প্রদান করি।',
            'keywords' => 'About Sajeb NEWS, বাংলাদেশ নিউজ, সংবাদ পোর্টাল, অনলাইন খবর',
            'canonical' => route('about'),
            'og_title' => 'আমাদের সম্পর্কে - Sajeb NEWS',
            'og_description' => 'Sajeb NEWS - বাংলাদেশের নির্ভরযোগ্য নিউজ পোর্টাল',
            'og_url' => route('about'),
        ];

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Sajeb NEWS',
            'url' => route('home'),
            'logo' => asset('logo.png'),
            'description' => 'বাংলাদেশের বিশ্বস্ত নিউজ পোর্টাল',
            'sameAs' => [
                'https://www.facebook.com/sajebnews',
                'https://www.twitter.com/sajebnews',
                'https://www.youtube.com/@sajebnews'
            ]
        ];

        return view('public.pages.about', compact('metaTags', 'schema', 'seoSettings', 'aboutContent'));
    }

    /**
     * Display Contact page
     */
    public function contact()
    {
        $metaTags = [
            'title' => 'যোগাযোগ করুন - Sajeb NEWS',
            'description' => 'Sajeb NEWS এর সাথে যোগাযোগ করুন। বিজ্ঞাপন, অংশীদারিত্ব বা অন্যান্য প্রশ্নের জন্য আমাদের সাথে যোগাযোগ করুন।',
            'keywords' => 'Contact Sajeb NEWS, যোগাযোগ, বিজ্ঞাপন, অংশীদারিত্ব',
            'canonical' => route('contact'),
            'og_title' => 'যোগাযোগ করুন - Sajeb NEWS',
            'og_description' => 'Sajeb NEWS অফিসের সাথে যোগাযোগ করুন',
            'og_url' => route('contact'),
        ];

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'ContactPage',
            'name' => 'Sajeb NEWS - যোগাযোগ',
            'url' => route('contact'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'email' => config('mail.from.address'),
                'telephone' => '+880-1XXX-XXXXXX'
            ]
        ];

        return view('public.pages.contact', compact('metaTags', 'schema'));
    }

    /**
     * Handle contact form submission
     */
    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:2000',
        ]);

        try {
            // Send email
            \Mail::to(config('mail.from.address'))->send(
                new \App\Mail\ContactFormMail($validated)
            );

            return back()->with('success', 'আপনার বার্তা সফলভাবে পাঠানো হয়েছে। শীঘ্রই আমরা যোগাযোগ করব।');
        } catch (\Exception $e) {
            return back()->with('error', 'বার্তা পাঠাতে সমস্যা হয়েছে। পরে চেষ্টা করুন।');
        }
    }

    /**
     * Display Privacy Policy page
     */
    public function privacy()
    {
        $metaTags = [
            'title' => 'গোপনীয়তা নীতি - Sajeb NEWS',
            'description' => 'Sajeb NEWS এর গোপনীয়তা নীতি। আমরা আপনার ব্যক্তিগত তথ্য সুরক্ষিত রাখি।',
            'keywords' => 'Privacy Policy, গোপনীয়তা নীতি, ডেটা সুরক্ষা',
            'canonical' => route('privacy'),
            'og_title' => 'গোপনীয়তা নীতি - Sajeb NEWS',
            'og_description' => 'Sajeb NEWS গোপনীয়তা নীতি এবং ডেটা সুরক্ষা',
            'og_url' => route('privacy'),
        ];

        return view('public.pages.privacy', compact('metaTags'));
    }

    /**
     * Display Terms and Conditions page
     */
    public function terms()
    {
        $metaTags = [
            'title' => 'শর্তাবলী - Sajeb NEWS',
            'description' => 'Sajeb NEWS এর শর্তাবলী এবং সেবার শর্তপ্রসঙ্গ।',
            'keywords' => 'Terms and Conditions, শর্তাবলী, সেবার শর্তপ্রসঙ্গ',
            'canonical' => route('terms'),
            'og_title' => 'শর্তাবলী - Sajeb NEWS',
            'og_description' => 'Sajeb NEWS এর পরিষেবা শর্তাবলী',
            'og_url' => route('terms'),
        ];

        return view('public.pages.terms', compact('metaTags'));
    }

    /**
     * Display Sitemap page
     */
    public function sitemap()
    {
        $metaTags = [
            'title' => 'সাইট ম্যাপ - Sajeb NEWS',
            'description' => 'Sajeb NEWS সাইট ম্যাপ। সমস্ত পেজ এবং অংশের লিঙ্ক দেখুন।',
            'keywords' => 'Sitemap, সাইট ম্যাপ, নেভিগেশন',
            'canonical' => route('sitemap'),
            'og_title' => 'সাইট ম্যাপ - Sajeb NEWS',
            'og_url' => route('sitemap'),
        ];

        $categories = Category::where('is_active', true)->get();
        $recentNews = News::where('status', 'published')->latest()->take(50)->get();

        return view('public.pages.sitemap', compact('metaTags', 'categories', 'recentNews'));
    }

    /**
     * Generate sitemap.xml
     */
    public function sitemapXml()
    {
        $urls = [];

        // Home page
        $urls[] = [
            'url' => route('home'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '1.0'
        ];

        // Static pages
        $staticPages = [
            ['route' => 'about', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['route' => 'contact', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['route' => 'privacy', 'changefreq' => 'yearly', 'priority' => '0.6'],
            ['route' => 'terms', 'changefreq' => 'yearly', 'priority' => '0.6'],
            ['route' => 'sitemap', 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['route' => 'live.index', 'changefreq' => 'daily', 'priority' => '0.8'],
        ];

        foreach ($staticPages as $page) {
            $urls[] = [
                'url' => route($page['route']),
                'lastmod' => now()->toAtomString(),
                'changefreq' => $page['changefreq'],
                'priority' => $page['priority']
            ];
        }

        // Published News
        $news = News::where('status', 'published')
            ->select('slug', 'updated_at', 'featured_image', 'title')
            ->limit(50000)
            ->get();

        foreach ($news as $item) {
            $entry = [
                'url' => route('news.show', ['news' => $item->slug]),
                'lastmod' => $item->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
            if ($item->featured_image) {
                $entry['image_url'] = asset('storage/' . $item->featured_image);
                $entry['image_title'] = $item->title;
            }
            $urls[] = $entry;
        }

        // Categories (exclude test/dummy categories)
        $categories = Category::where('is_active', true)
            ->where('slug', 'not like', 'test%')
            ->where('slug', 'not like', 'demo%')
            ->where('slug', 'not like', 'dummy%')
            ->select('slug', 'updated_at')
            ->get();

        foreach ($categories as $category) {
            $urls[] = [
                'url' => route('category.show', ['category' => $category->slug]),
                'lastmod' => $category->updated_at->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.7'
            ];
        }

        // Author pages (E-E-A-T)
        $authors = \App\Models\User::whereHas('newsArticles', fn($q) => $q->where('status', 'published'))
            ->select('id', 'updated_at')
            ->get();

        foreach ($authors as $author) {
            $urls[] = [
                'url' => route('author.show', $author->id),
                'lastmod' => $author->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.6'
            ];
        }

        // Tags with published articles
        $tags = \App\Models\Tag::whereHas('news', fn($q) => $q->where('status', 'published'))
            ->select('slug', 'updated_at')
            ->get();

        foreach ($tags as $tag) {
            $urls[] = [
                'url' => route('tag.show', $tag->slug),
                'lastmod' => $tag->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.5'
            ];
        }

        return response()->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'text/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * Google News Sitemap — last 48 hours of published news
     */
    public function newsSitemapXml()
    {
        $seo = SeoSetting::first();
        $siteName = $seo?->site_name ?: 'সজীব নিউজ';

        $news = News::where('status', 'published')
            ->where('published_at', '>=', now()->subHours(48))
            ->with('category')
            ->select('id', 'slug', 'title', 'published_at', 'category_id', 'meta_keywords', 'featured_image')
            ->latest('published_at')
            ->limit(1000)
            ->get();

        return response()->view('news-sitemap', [
            'news' => $news,
            'siteName' => $siteName,
        ])->header('Content-Type', 'text/xml; charset=UTF-8')
          ->header('Cache-Control', 'public, max-age=900');
    }

    /**
     * RSS Feed — latest 50 published news
     */
    public function rssFeed()
    {
        $seo = SeoSetting::first();
        $siteName = $seo?->site_name ?: 'সজীব নিউজ';
        $siteDescription = $seo?->site_description ?: 'বাংলাদেশের নির্ভরযোগ্য অনলাইন সংবাদ পোর্টাল';

        $news = News::where('status', 'published')
            ->with(['category', 'author'])
            ->latest('published_at')
            ->limit(50)
            ->get();

        return response()->view('rss-feed', [
            'news' => $news,
            'siteName' => $siteName,
            'siteDescription' => $siteDescription,
            'siteUrl' => url('/'),
        ])->header('Content-Type', 'application/rss+xml; charset=UTF-8')
          ->header('Cache-Control', 'public, max-age=1800');
    }

    /**
     * Generate llm.txt
     */
    public function llmTxt()
    {
        $seo = SeoSetting::first();
        $siteName = $seo?->site_name ?: 'সজীব নিউজ';
        $siteUrl = $seo?->site_url ?: config('app.url');
        $siteDescription = $seo?->site_description ?: 'বাংলাদেশের বিশ্বস্ত নিউজ পোর্টাল';

        $content = "# {$siteName}\n\n";
        $content .= "> {$siteDescription}\n\n";

        $content .= "## Overview\n";
        $content .= "- **Name**: {$siteName}\n";
        $content .= "- **URL**: {$siteUrl}\n";
        $content .= "- **Language**: Bengali (বাংলা) / bn\n";
        $content .= "- **Type**: Online News Portal\n";
        $content .= "- **Country**: Bangladesh (BD)\n";
        if ($seo?->editor_publisher) {
            $content .= "- **Editor & Publisher**: {$seo->editor_publisher}\n";
        }
        $content .= "- **RSS Feed**: " . url('/feed') . "\n";
        $content .= "- **Sitemap**: " . url('/sitemap.xml') . "\n\n";

        $content .= "## Content Structure\n\n";

        $categories = Category::where('is_active', true)
            ->where('slug', 'not like', 'test%')
            ->withCount(['news' => fn($q) => $q->where('status', 'published')])
            ->orderByDesc('news_count')
            ->get();

        $content .= "### Categories ({$categories->count()})\n";
        foreach ($categories as $category) {
            $content .= "- **{$category->name}** ({$category->news_count} articles): " . route('category.show', $category->slug) . "\n";
            if ($category->description) {
                $content .= "  {$category->description}\n";
            }
        }

        $content .= "\n### Authors\n";
        $authors = \App\Models\User::whereHas('newsArticles', fn($q) => $q->where('status', 'published'))
            ->withCount(['newsArticles' => fn($q) => $q->where('status', 'published')])
            ->orderByDesc('news_articles_count')
            ->get();
        foreach ($authors as $author) {
            $content .= "- **{$author->name}** ({$author->news_articles_count} articles): " . route('author.show', $author->id) . "\n";
        }

        $content .= "\n## Latest News (Top 20)\n\n";
        $latestNews = News::where('status', 'published')
            ->with('category')
            ->orderBy('published_at', 'desc')
            ->take(20)
            ->get();

        foreach ($latestNews as $news) {
            $categoryName = $news->category?->name ?? 'N/A';
            $content .= "### {$news->title}\n";
            $content .= "- **Published**: {$news->published_at->format('Y-m-d H:i')}\n";
            $content .= "- **Category**: {$categoryName}\n";
            $content .= "- **URL**: " . route('news.show', $news->slug) . "\n";
            if ($news->excerpt) {
                $content .= "- **Summary**: {$news->excerpt}\n";
            }
            $content .= "\n";
        }

        $content .= "## Important Pages\n";
        $content .= "- Home: " . route('home') . "\n";
        $content .= "- About: " . route('about') . "\n";
        $content .= "- Contact: " . route('contact') . "\n";
        $content .= "- Privacy Policy: " . route('privacy') . "\n";
        $content .= "- Terms & Conditions: " . route('terms') . "\n";
        $content .= "- Live Stream: " . route('live.index') . "\n";
        $content .= "- Sitemap: " . route('sitemap') . "\n";

        $content .= "\n## Statistics\n";
        $content .= "- Total Published Articles: " . News::where('status', 'published')->count() . "\n";
        $content .= "- Active Categories: " . $categories->count() . "\n";
        $content .= "- Active Authors: " . $authors->count() . "\n";
        $content .= "- Last Updated: " . now()->format('Y-m-d H:i:s') . " (UTC+6)\n";

        return response($content)
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Content-Disposition', 'inline; filename="llm.txt"');
    }

    public function robotsTxt()
    {
        $host     = url('/');
        $sitemap  = url('/sitemap.xml');
        $llmTxt   = url('/llm.txt');

        $content = <<<ROBOTS
# Sajeb NEWS - Robots.txt
# This file instructs web crawlers how to index our site

User-agent: *
Allow: /
Allow: /news/
Allow: /category/
Allow: /tag/
Allow: /author/
Allow: /about
Allow: /contact
Allow: /privacy-policy
Allow: /terms-and-conditions
Allow: /sitemap
Allow: /llm.txt

# Disallow private/admin pages
Disallow: /admin/
Disallow: /login
Disallow: /register
Disallow: /password-reset
Disallow: /dashboard
Disallow: /profile
Disallow: /api/admin/

# Disallow search and filter pages that create duplicate content
Disallow: /*?page=
Disallow: /*?sort=
Disallow: /*?filter=
Disallow: /*?s=

# Allow access to CSS, JS, and images
Allow: /*.css$
Allow: /*.js$
Allow: /*.jpg$
Allow: /*.jpeg$
Allow: /*.png$
Allow: /*.gif$
Allow: /*.svg$
Allow: /storage/

# Sitemap location
Sitemap: {$sitemap}
Sitemap: {$host}/news-sitemap.xml
Sitemap: {$llmTxt}

# Crawl delay for respectful crawling
Crawl-delay: 1

# Specific rules for known bots
User-agent: Googlebot
Crawl-delay: 0

User-agent: Bingbot
Crawl-delay: 1

# Disallow bad bots
User-agent: MJ12bot
Disallow: /

User-agent: AhrefsBot
Crawl-delay: 10

User-agent: SemrushBot
Crawl-delay: 10
ROBOTS;

        return response($content)
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=86400');
    }
}
