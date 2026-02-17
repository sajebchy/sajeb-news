<?php

namespace Database\Seeders;

use App\Models\VisitorAnalytic;
use App\Models\News;
use Illuminate\Database\Seeder;

class VisitorAnalyticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $news = News::where('status', 'published')->limit(5)->get();

        $sources = ['google', 'facebook', 'twitter', 'chatgpt', 'direct', 'bing'];
        $countries = ['Bangladesh', 'India', 'USA', 'UK', 'Canada', 'Australia', 'Germany', 'France'];
        $cities = ['Dhaka', 'Kolkata', 'New York', 'London', 'Toronto', 'Sydney', 'Berlin', 'Paris'];
        $devices = ['Mobile', 'Tablet', 'Desktop'];
        $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge'];
        $os = ['Windows', 'macOS', 'Linux', 'iOS', 'Android'];

        foreach ($news as $newsItem) {
            // Create 10-50 visitor records per news
            $visitorCount = rand(10, 50);

            for ($i = 0; $i < $visitorCount; $i++) {
                VisitorAnalytic::create([
                    'news_id' => $newsItem->id,
                    'visitor_ip' => fake()->ipv4(),
                    'visitor_country' => $countries[array_rand($countries)],
                    'visitor_city' => $cities[array_rand($cities)],
                    'visitor_device' => $devices[array_rand($devices)],
                    'referrer_source' => $sources[array_rand($sources)],
                    'referrer_url' => fake()->url(),
                    'time_spent_seconds' => rand(30, 900), // 30 seconds to 15 minutes
                    'scroll_percentage' => rand(20, 100),
                    'completed_reading' => rand(0, 1) === 1,
                    'browser' => $browsers[array_rand($browsers)],
                    'os' => $os[array_rand($os)],
                    'visit_date' => fake()->dateTimeBetween('-30 days', 'now'),
                ]);
            }
        }
    }
}
