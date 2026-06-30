<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleNewsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        if (!$admin) {
            return;
        }

        // Create categories with featured_order
        $categories = [
            ['name' => 'জাতীয়', 'slug' => 'national', 'featured_order' => 1],
            ['name' => 'আন্তর্জাতিক', 'slug' => 'international', 'featured_order' => 2],
            ['name' => 'রাজনীতি', 'slug' => 'politics', 'featured_order' => 3],
            ['name' => 'অর্থনীতি', 'slug' => 'economy', 'featured_order' => 4],
            ['name' => 'খেলাধুলা', 'slug' => 'sports', 'featured_order' => 5],
            ['name' => 'বিনোদন', 'slug' => 'entertainment', 'featured_order' => 6],
            ['name' => 'প্রযুক্তি', 'slug' => 'technology', 'featured_order' => 7],
            ['name' => 'স্বাস্থ্য', 'slug' => 'health', 'featured_order' => null],
            ['name' => 'শিক্ষা', 'slug' => 'education', 'featured_order' => null],
        ];

        $catIds = [];
        foreach ($categories as $cat) {
            $existing = Category::where('slug', $cat['slug'])->first();
            if ($existing) {
                $existing->update(['featured_order' => $cat['featured_order'], 'is_active' => true]);
                $catIds[$cat['slug']] = $existing->id;
            } else {
                $c = Category::create([
                    'name' => $cat['name'],
                    'slug' => $cat['slug'],
                    'is_active' => true,
                    'featured_order' => $cat['featured_order'],
                ]);
                $catIds[$cat['slug']] = $c->id;
            }
        }

        $articles = [
            // Featured + Breaking news
            [
                'title' => 'প্রধানমন্ত্রীর সঙ্গে বিভিন্ন দেশের রাষ্ট্রদূতদের সাক্ষাৎ',
                'excerpt' => 'আজ সকালে প্রধানমন্ত্রীর কার্যালয়ে বিভিন্ন দেশের রাষ্ট্রদূতরা সাক্ষাৎ করেন। এ সময় দ্বিপাক্ষিক সম্পর্ক উন্নয়নসহ নানা বিষয় আলোচনা হয়।',
                'content' => '<p>আজ সকালে প্রধানমন্ত্রীর কার্যালয়ে বিভিন্ন দেশের রাষ্ট্রদূতরা পৃথকভাবে সাক্ষাৎ করেন। এ সময় দ্বিপাক্ষিক সম্পর্ক উন্নয়ন, বাণিজ্য, বিনিয়োগসহ নানা বিষয়ে আলোচনা হয়।</p><p>প্রধানমন্ত্রীর প্রেস সচিব জানান, এই বৈঠকগুলো অত্যন্ত ফলপ্রসূ হয়েছে এবং উভয় দেশের মধ্যে সম্পর্ক আরও মজবুত হবে বলে আশা প্রকাশ করা হয়েছে।</p>',
                'category' => 'national',
                'is_featured' => true,
                'is_breaking' => true,
                'views' => 15420,
                'featured_image' => 'https://picsum.photos/seed/news1/800/450',
            ],
            [
                'title' => 'বাংলাদেশের অর্থনৈতিক প্রবৃদ্ধি ৬.৫ শতাংশ ছাড়িয়েছে',
                'excerpt' => 'চলতি অর্থবছরে বাংলাদেশের জিডিপি প্রবৃদ্ধি ৬.৫ শতাংশ অতিক্রম করেছে বলে অর্থ মন্ত্রণালয় জানিয়েছে।',
                'content' => '<p>চলতি অর্থবছরে বাংলাদেশের জিডিপি প্রবৃদ্ধি ৬.৫ শতাংশ অতিক্রম করেছে বলে অর্থ মন্ত্রণালয়ের এক প্রতিবেদনে জানানো হয়েছে।</p><p>রপ্তানি আয় বৃদ্ধি ও রেমিট্যান্স প্রবাহ এই প্রবৃদ্ধিতে মূল ভূমিকা রেখেছে।</p>',
                'category' => 'economy',
                'is_featured' => true,
                'is_breaking' => false,
                'views' => 8930,
                'featured_image' => 'https://picsum.photos/seed/news2/800/450',
            ],
            [
                'title' => 'ঢাকা-চট্টগ্রাম এক্সপ্রেসওয়ে নির্মাণ কাজ শুরু',
                'excerpt' => 'দেশের সবচেয়ে বড় অবকাঠামো প্রকল্প ঢাকা-চট্টগ্রাম এক্সপ্রেসওয়ের নির্মাণ কাজ আনুষ্ঠানিকভাবে শুরু হয়েছে।',
                'content' => '<p>দেশের সবচেয়ে বড় অবকাঠামো প্রকল্প ঢাকা-চট্টগ্রাম এক্সপ্রেসওয়ের নির্মাণ কাজ আনুষ্ঠানিকভাবে শুরু হয়েছে। এই প্রকল্পে ব্যয় হবে প্রায় ৩৫ হাজার কোটি টাকা।</p>',
                'category' => 'national',
                'is_featured' => true,
                'is_breaking' => false,
                'views' => 12750,
                'featured_image' => 'https://picsum.photos/seed/news3/800/450',
            ],
            [
                'title' => 'জাতিসংঘে বাংলাদেশের মানবাধিকার প্রতিবেদন উপস্থাপন',
                'excerpt' => 'জেনেভায় জাতিসংঘের মানবাধিকার পরিষদে বাংলাদেশের প্রতিনিধি দল দেশের মানবাধিকার পরিস্থিতির উপর প্রতিবেদন উপস্থাপন করেছে।',
                'content' => '<p>জেনেভায় জাতিসংঘের মানবাধিকার পরিষদে বাংলাদেশের প্রতিনিধি দল দেশের মানবাধিকার পরিস্থিতির উপর প্রতিবেদন উপস্থাপন করেছে।</p>',
                'category' => 'international',
                'is_featured' => true,
                'is_breaking' => false,
                'views' => 6540,
                'featured_image' => 'https://picsum.photos/seed/news4/800/450',
            ],
            [
                'title' => 'বন্যা পরিস্থিতি নিয়ন্ত্রণে সেনাবাহিনী মোতায়েন',
                'excerpt' => 'উত্তরাঞ্চলের বন্যা পরিস্থিতি মোকাবেলায় সেনাবাহিনীর বিশেষ দল মোতায়েন করা হয়েছে।',
                'content' => '<p>উত্তরাঞ্চলের বন্যা পরিস্থিতি মোকাবেলায় সেনাবাহিনীর বিশেষ দল মোতায়েন করা হয়েছে। ইতোমধ্যে ৫০ হাজারেরও বেশি মানুষকে নিরাপদ আশ্রয়ে সরিয়ে নেওয়া হয়েছে।</p>',
                'category' => 'national',
                'is_featured' => false,
                'is_breaking' => true,
                'views' => 22100,
                'featured_image' => 'https://picsum.photos/seed/news5/800/450',
            ],
            // National
            [
                'title' => 'নতুন শিক্ষানীতি বাস্তবায়নে ব্যাপক প্রস্তুতি',
                'excerpt' => 'আসন্ন শিক্ষাবর্ষ থেকে নতুন শিক্ষানীতি পুরোপুরি বাস্তবায়ন করা হবে বলে শিক্ষা মন্ত্রণালয় জানিয়েছে।',
                'content' => '<p>আসন্ন শিক্ষাবর্ষ থেকে নতুন শিক্ষানীতি পুরোপুরি বাস্তবায়ন করা হবে বলে শিক্ষা মন্ত্রণালয় জানিয়েছে।</p>',
                'category' => 'national',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 4200,
                'featured_image' => 'https://picsum.photos/seed/news6/800/450',
            ],
            [
                'title' => 'রাজধানীতে তাপমাত্রা বৃদ্ধি, সতর্কতা জারি',
                'excerpt' => 'ঢাকায় তাপমাত্রা ৪০ ডিগ্রি ছাড়িয়ে যাওয়ায় স্বাস্থ্য অধিদপ্তর সতর্কতা জারি করেছে।',
                'content' => '<p>ঢাকায় তাপমাত্রা ৪০ ডিগ্রি সেলসিয়াস ছাড়িয়ে যাওয়ায় স্বাস্থ্য অধিদপ্তর সতর্কতা জারি করেছে।</p>',
                'category' => 'national',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 3890,
                'featured_image' => 'https://picsum.photos/seed/news7/800/450',
            ],
            [
                'title' => 'পদ্মা সেতুতে রেল সার্ভিস চালু হচ্ছে আগামী মাসে',
                'excerpt' => 'পদ্মা সেতু দিয়ে ট্রেন চলাচল আগামী মাস থেকে শুরু হবে বলে রেলমন্ত্রী ঘোষণা দিয়েছেন।',
                'content' => '<p>পদ্মা সেতু দিয়ে ট্রেন চলাচল আগামী মাস থেকে শুরু হবে বলে রেলমন্ত্রী ঘোষণা দিয়েছেন।</p>',
                'category' => 'national',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 9870,
                'featured_image' => 'https://picsum.photos/seed/news8/800/450',
            ],
            [
                'title' => 'ডিজিটাল বাংলাদেশ থেকে স্মার্ট বাংলাদেশ: লক্ষ্যমাত্রা অর্জনে এগিয়ে',
                'excerpt' => 'স্মার্ট বাংলাদেশ গড়ার লক্ষ্যে সরকার ডিজিটাল সেবা সম্প্রসারণে বিনিয়োগ বাড়াচ্ছে।',
                'content' => '<p>স্মার্ট বাংলাদেশ গড়ার লক্ষ্যে সরকার ডিজিটাল সেবা সম্প্রসারণে বিনিয়োগ বাড়াচ্ছে।</p>',
                'category' => 'national',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 5430,
                'featured_image' => 'https://picsum.photos/seed/news9/800/450',
            ],
            // International
            [
                'title' => 'ভারত-বাংলাদেশ পানি চুক্তি নবায়নে আলোচনা শুরু',
                'excerpt' => 'তিস্তাসহ অন্যান্য অভিন্ন নদীর পানি বণ্টন নিয়ে ভারত ও বাংলাদেশের মধ্যে আলোচনা শুরু হয়েছে।',
                'content' => '<p>তিস্তাসহ অন্যান্য অভিন্ন নদীর পানি বণ্টন নিয়ে ভারত ও বাংলাদেশের মধ্যে উচ্চপর্যায়ের আলোচনা শুরু হয়েছে।</p>',
                'category' => 'international',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 7820,
                'featured_image' => 'https://picsum.photos/seed/news10/800/450',
            ],
            [
                'title' => 'রোহিঙ্গা সংকট: আন্তর্জাতিক সম্প্রদায়ের সহায়তার আহ্বান',
                'excerpt' => 'রোহিঙ্গা শরণার্থী সংকট মোকাবেলায় বাংলাদেশ আন্তর্জাতিক সম্প্রদায়ের কাছে আরও সহায়তার আহ্বান জানিয়েছে।',
                'content' => '<p>রোহিঙ্গা শরণার্থী সংকট মোকাবেলায় বাংলাদেশ আন্তর্জাতিক সম্প্রদায়ের কাছে আরও সহায়তার আহ্বান জানিয়েছে।</p>',
                'category' => 'international',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 11200,
                'featured_image' => 'https://picsum.photos/seed/news11/800/450',
            ],
            [
                'title' => 'মধ্যপ্রাচ্যে বাংলাদেশের শ্রমবাজার প্রসারিত হচ্ছে',
                'excerpt' => 'সৌদি আরব ও কাতারে বাংলাদেশি শ্রমিকদের চাহিদা উল্লেখযোগ্যভাবে বৃদ্ধি পেয়েছে।',
                'content' => '<p>সৌদি আরব ও কাতারে বাংলাদেশি শ্রমিকদের চাহিদা উল্লেখযোগ্যভাবে বৃদ্ধি পেয়েছে।</p>',
                'category' => 'international',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 6100,
                'featured_image' => 'https://picsum.photos/seed/news12/800/450',
            ],
            // Politics
            [
                'title' => 'নির্বাচন কমিশন নতুন ভোটার তালিকা প্রস্তুত করছে',
                'excerpt' => 'আসন্ন নির্বাচনকে সামনে রেখে নির্বাচন কমিশন হালনাগাদ ভোটার তালিকা প্রস্তুত করছে।',
                'content' => '<p>আসন্ন নির্বাচনকে সামনে রেখে নির্বাচন কমিশন হালনাগাদ ভোটার তালিকা প্রস্তুত করছে।</p>',
                'category' => 'politics',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 5670,
                'featured_image' => 'https://picsum.photos/seed/news13/800/450',
            ],
            [
                'title' => 'সংসদে বিরোধী দলের ওয়াকআউট',
                'excerpt' => 'বাজেট অধিবেশনে বিতর্কের এক পর্যায়ে বিরোধী দলের সংসদ সদস্যরা ওয়াকআউট করেন।',
                'content' => '<p>বাজেট অধিবেশনে বিতর্কের এক পর্যায়ে বিরোধী দলের সংসদ সদস্যরা ওয়াকআউট করেন।</p>',
                'category' => 'politics',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 8450,
                'featured_image' => 'https://picsum.photos/seed/news14/800/450',
            ],
            [
                'title' => 'স্থানীয় সরকার নির্বাচনের তারিখ ঘোষণা',
                'excerpt' => 'ইউনিয়ন পরিষদ নির্বাচনের তারিখ ঘোষণা করেছে নির্বাচন কমিশন।',
                'content' => '<p>ইউনিয়ন পরিষদ নির্বাচনের তারিখ ঘোষণা করেছে নির্বাচন কমিশন।</p>',
                'category' => 'politics',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 4320,
                'featured_image' => 'https://picsum.photos/seed/news15/800/450',
            ],
            // Economy
            [
                'title' => 'রেমিট্যান্স প্রবাহে রেকর্ড, মে মাসে ২.৫ বিলিয়ন ডলার',
                'excerpt' => 'চলতি বছরের মে মাসে প্রবাসীরা ২.৫ বিলিয়ন মার্কিন ডলার রেমিট্যান্স পাঠিয়েছেন।',
                'content' => '<p>চলতি বছরের মে মাসে প্রবাসীরা ২.৫ বিলিয়ন মার্কিন ডলার রেমিট্যান্স পাঠিয়েছেন, যা গত বছরের একই মাসের তুলনায় ১৮ শতাংশ বেশি।</p>',
                'category' => 'economy',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 7650,
                'featured_image' => 'https://picsum.photos/seed/news16/800/450',
            ],
            [
                'title' => 'শেয়ারবাজারে উর্ধ্বমুখী প্রবণতা অব্যাহত',
                'excerpt' => 'ঢাকা স্টক এক্সচেঞ্জে টানা পাঁচ দিন ধরে শেয়ার সূচকে ঊর্ধ্বমুখী প্রবণতা অব্যাহত রয়েছে।',
                'content' => '<p>ঢাকা স্টক এক্সচেঞ্জে টানা পাঁচ দিন ধরে শেয়ার সূচকে ঊর্ধ্বমুখী প্রবণতা অব্যাহত রয়েছে।</p>',
                'category' => 'economy',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 4980,
                'featured_image' => 'https://picsum.photos/seed/news17/800/450',
            ],
            [
                'title' => 'এলপিজি গ্যাসের দাম কমল',
                'excerpt' => 'সিলিন্ডার প্রতি এলপিজি গ্যাসের দাম ৫০ টাকা কমানোর ঘোষণা দিয়েছে জ্বালানি নিয়ন্ত্রক সংস্থা।',
                'content' => '<p>সিলিন্ডার প্রতি এলপিজি গ্যাসের দাম ৫০ টাকা কমানোর ঘোষণা দিয়েছে জ্বালানি নিয়ন্ত্রক সংস্থা।</p>',
                'category' => 'economy',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 13500,
                'featured_image' => 'https://picsum.photos/seed/news18/800/450',
            ],
            // Sports
            [
                'title' => 'বাংলাদেশ ক্রিকেট দল টি-টোয়েন্টি সিরিজ জিতলো',
                'excerpt' => 'শ্রীলঙ্কার বিপক্ষে তিন ম্যাচের টি-টোয়েন্টি সিরিজ ২-১ ব্যবধানে জিতেছে বাংলাদেশ।',
                'content' => '<p>শ্রীলঙ্কার বিপক্ষে তিন ম্যাচের টি-টোয়েন্টি সিরিজ ২-১ ব্যবধানে জিতেছে বাংলাদেশ।</p>',
                'category' => 'sports',
                'is_featured' => false,
                'is_breaking' => true,
                'views' => 28900,
                'featured_image' => 'https://picsum.photos/seed/news19/800/450',
            ],
            [
                'title' => 'সাফ চ্যাম্পিয়নশিপে বাংলাদেশ ফুটবল দলের দুর্দান্ত জয়',
                'excerpt' => 'সাফ চ্যাম্পিয়নশিপে নেপালকে ৩-১ গোলে হারিয়ে সেমিফাইনালে উঠেছে বাংলাদেশ।',
                'content' => '<p>সাফ চ্যাম্পিয়নশিপে নেপালকে ৩-১ গোলে হারিয়ে সেমিফাইনালে উঠেছে বাংলাদেশ।</p>',
                'category' => 'sports',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 19700,
                'featured_image' => 'https://picsum.photos/seed/news20/800/450',
            ],
            [
                'title' => 'অলিম্পিকে বাংলাদেশের পাঁচ ক্রীড়াবিদ',
                'excerpt' => 'আসন্ন অলিম্পিকে বাংলাদেশের পাঁচজন ক্রীড়াবিদ বিভিন্ন ইভেন্টে অংশ নেবেন।',
                'content' => '<p>আসন্ন অলিম্পিকে বাংলাদেশের পাঁচজন ক্রীড়াবিদ বিভিন্ন ইভেন্টে অংশ নেবেন।</p>',
                'category' => 'sports',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 6320,
                'featured_image' => 'https://picsum.photos/seed/news21/800/450',
            ],
            // Entertainment
            [
                'title' => 'ঈদুল আজহায় মুক্তি পেল বহুল প্রতীক্ষিত বাংলা চলচ্চিত্র',
                'excerpt' => 'ঈদুল আজহায় একযোগে মুক্তি পেয়েছে বেশ কয়েকটি নতুন বাংলা চলচ্চিত্র।',
                'content' => '<p>ঈদুল আজহায় একযোগে মুক্তি পেয়েছে বেশ কয়েকটি নতুন বাংলা চলচ্চিত্র।</p>',
                'category' => 'entertainment',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 15600,
                'featured_image' => 'https://picsum.photos/seed/news22/800/450',
            ],
            [
                'title' => 'বাংলাদেশি শিল্পীর গান আন্তর্জাতিক চার্টে',
                'excerpt' => 'বাংলাদেশি কণ্ঠশিল্পী আন্তর্জাতিক মিউজিক প্ল্যাটফর্মে শীর্ষ ট্রেন্ডিং তালিকায় উঠে এসেছেন।',
                'content' => '<p>বাংলাদেশি কণ্ঠশিল্পী আন্তর্জাতিক মিউজিক প্ল্যাটফর্মে শীর্ষ ট্রেন্ডিং তালিকায় উঠে এসেছেন।</p>',
                'category' => 'entertainment',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 22300,
                'featured_image' => 'https://picsum.photos/seed/news23/800/450',
            ],
            [
                'title' => 'ঢাকায় আন্তর্জাতিক চলচ্চিত্র উৎসব শুরু',
                'excerpt' => 'রাজধানী ঢাকায় সপ্তাহব্যাপী আন্তর্জাতিক চলচ্চিত্র উৎসব শুরু হয়েছে।',
                'content' => '<p>রাজধানী ঢাকায় সপ্তাহব্যাপী আন্তর্জাতিক চলচ্চিত্র উৎসব শুরু হয়েছে।</p>',
                'category' => 'entertainment',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 8900,
                'featured_image' => 'https://picsum.photos/seed/news24/800/450',
            ],
            // Technology
            [
                'title' => 'বাংলাদেশে ৫জি নেটওয়ার্ক চালুর পথে',
                'excerpt' => 'দেশের প্রধান টেলিযোগাযোগ কোম্পানিগুলো ৫জি নেটওয়ার্ক চালুর প্রস্তুতি নিচ্ছে।',
                'content' => '<p>দেশের প্রধান টেলিযোগাযোগ কোম্পানিগুলো ৫জি নেটওয়ার্ক চালুর প্রস্তুতি নিচ্ছে।</p>',
                'category' => 'technology',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 14200,
                'featured_image' => 'https://picsum.photos/seed/news25/800/450',
            ],
            [
                'title' => 'দেশীয় সফটওয়্যার বিশ্ববাজারে ৫০ কোটি ডলার আয়',
                'excerpt' => 'বাংলাদেশের সফটওয়্যার শিল্প এ বছর বিশ্ববাজার থেকে ৫০ কোটি ডলার আয় করেছে।',
                'content' => '<p>বাংলাদেশের সফটওয়্যার শিল্প এ বছর বিশ্ববাজার থেকে ৫০ কোটি ডলার আয় করেছে।</p>',
                'category' => 'technology',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 9850,
                'featured_image' => 'https://picsum.photos/seed/news26/800/450',
            ],
            [
                'title' => 'কৃত্রিম বুদ্ধিমত্তা ব্যবহারে এগিয়ে বাংলাদেশের তরুণরা',
                'excerpt' => 'বিশ্বের বিভিন্ন দেশে বাংলাদেশি তরুণরা আর্টিফিশিয়াল ইন্টেলিজেন্স প্রযুক্তিতে সাফল্য দেখাচ্ছেন।',
                'content' => '<p>বিশ্বের বিভিন্ন দেশে বাংলাদেশি তরুণরা আর্টিফিশিয়াল ইন্টেলিজেন্স প্রযুক্তিতে সাফল্য দেখাচ্ছেন।</p>',
                'category' => 'technology',
                'is_featured' => false,
                'is_breaking' => false,
                'views' => 11400,
                'featured_image' => 'https://picsum.photos/seed/news27/800/450',
            ],
        ];

        $now = now();
        foreach ($articles as $i => $article) {
            $catId = $catIds[$article['category']] ?? $catIds['national'];
            $slug = Str::slug($article['title']) ?: 'news-' . ($i + 1);

            // Make slug unique
            $uniqueSlug = $slug;
            $count = 1;
            while (News::where('slug', $uniqueSlug)->exists()) {
                $uniqueSlug = $slug . '-' . $count++;
            }

            News::create([
                'title' => $article['title'],
                'slug' => $uniqueSlug,
                'content' => $article['content'],
                'excerpt' => $article['excerpt'],
                'featured_image' => $article['featured_image'],
                'category_id' => $catId,
                'author_id' => $admin->id,
                'status' => 'published',
                'is_featured' => $article['is_featured'],
                'is_breaking' => $article['is_breaking'],
                'published_at' => $now->copy()->subHours($i * 2),
                'views' => $article['views'],
                'reading_time' => rand(2, 8),
            ]);
        }
    }
}
