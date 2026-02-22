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

        $categories = Category::where('is_published', true)->get();
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
            ->select('slug', 'updated_at')
            ->limit(50000)
            ->get();

        foreach ($news as $item) {
            $urls[] = [
                'url' => route('news.show', ['news' => $item->slug]),
                'lastmod' => $item->updated_at->toAtomString(),
                'changefreq' => 'never',
                'priority' => '0.8'
            ];
        }

        // Categories
        $categories = Category::where('is_published', true)
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

        return response()->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'text/xml; charset=UTF-8');
    }

    /**
     * Generate llm.txt
     */
    public function llmTxt()
    {
        $content = "# Sajeb NEWS - Large Language Model Information File\n\n";
        $content .= "## Website Information\n";
        $content .= "- Name: Sajeb NEWS\n";
        $content .= "- URL: " . config('app.url') . "\n";
        $content .= "- Description: বাংলাদেশের বিশ্বস্ত নিউজ পোর্টাল\n";
        $content .= "- Language: Bengali (বাংলা)\n\n";

        $content .= "## Latest News (Top 20)\n";
        $latestNews = News::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(20)
            ->get();

        foreach ($latestNews as $news) {
            $categoryName = $news->category ? $news->category->name : 'N/A';
            $content .= "- **{$news->title}** (Published: {$news->published_at->format('Y-m-d H:i')})\n";
            $content .= "  Category: {$categoryName}\n";
            $content .= "  URL: " . route('news.show', ['news' => $news->slug]) . "\n";
            $content .= "  Views: {$news->views}\n\n";
        }

        $content .= "## Categories\n";
        $categories = Category::where('is_published', true)->get();
        foreach ($categories as $category) {
            $content .= "- {$category->name}: " . route('category.show', ['category' => $category->slug]) . "\n";
        }

        $content .= "\n## Important Pages\n";
        $content .= "- Home: " . route('home') . "\n";
        $content .= "- About: " . route('about') . "\n";
        $content .= "- Contact: " . route('contact') . "\n";
        $content .= "- Privacy Policy: " . route('privacy') . "\n";
        $content .= "- Terms & Conditions: " . route('terms') . "\n";
        $content .= "- Live Stream: " . route('live.index') . "\n";
        $content .= "- Sitemap: " . route('sitemap') . "\n";

        $content .= "\n## Website Statistics\n";
        $content .= "- Total News: " . News::where('status', 'published')->count() . "\n";
        $content .= "- Total Categories: " . Category::where('is_published', true)->count() . "\n";
        $content .= "- Last Updated: " . now()->format('Y-m-d H:i:s') . "\n";

        return response($content)
            ->header('Content-Type', 'text/plain; charset=UTF-8')
            ->header('Content-Disposition', 'inline; filename="llm.txt"');
    }
}
