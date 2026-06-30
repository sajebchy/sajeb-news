<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use Illuminate\Database\Seeder;

class DummyAdsSeeder extends Seeder
{
    public function run(): void
    {
        $dummyAds = [
            // placement => [image dimensions, label, bg color, text color]
            'header_top' => [
                'image_url'       => 'https://placehold.co/970x90/004e9f/ffffff?text=সজীব+নিউজ+%7C+Header+Banner+970×90',
                'ad_url'          => 'https://example.com',
                'alt_text'        => 'হেডার ব্যানার বিজ্ঞাপন',
                'code'            => '',
                'advertiser_name' => 'Sajeb Ads Demo',
                'advertiser_email'=> 'ads@example.com',
            ],
            'navigation_sticky' => [
                'image_url'       => 'https://placehold.co/728x90/ab3500/ffffff?text=Sticky+Navigation+Banner+728×90',
                'ad_url'          => 'https://example.com',
                'alt_text'        => 'নেভিগেশন স্টিকি বিজ্ঞাপন',
                'code'            => '',
                'advertiser_name' => 'Sajeb Ads Demo',
                'advertiser_email'=> 'ads@example.com',
            ],
            'homepage_top' => [
                'image_url'       => 'https://placehold.co/970x250/005e2c/ffffff?text=Homepage+Top+Banner+970×250',
                'ad_url'          => 'https://example.com',
                'alt_text'        => 'হোমপেইজ টপ ব্যানার',
                'code'            => '',
                'advertiser_name' => 'Sajeb Ads Demo',
                'advertiser_email'=> 'ads@example.com',
            ],
            'sidebar_medium_rectangle' => [
                'image_url'       => 'https://placehold.co/300x250/6750A4/ffffff?text=Sidebar+300×250',
                'ad_url'          => 'https://example.com',
                'alt_text'        => 'সাইডবার মিডিয়াম রেকট্যাঙ্গেল',
                'code'            => '',
                'advertiser_name' => 'Sajeb Ads Demo',
                'advertiser_email'=> 'ads@example.com',
            ],
            'sidebar_half_page' => [
                'image_url'       => 'https://placehold.co/300x600/004e9f/ffffff?text=Sidebar+Half+Page+300×600',
                'ad_url'          => 'https://example.com',
                'alt_text'        => 'সাইডবার হাফ পেইজ',
                'code'            => '',
                'advertiser_name' => 'Sajeb Ads Demo',
                'advertiser_email'=> 'ads@example.com',
            ],
            'article_2nd_paragraph' => [
                'image_url'       => 'https://placehold.co/728x90/ab3500/ffffff?text=Article+Inline+Ad+728×90',
                'ad_url'          => 'https://example.com',
                'alt_text'        => 'আর্টিকেল ইনলাইন বিজ্ঞাপন',
                'code'            => '',
                'advertiser_name' => 'Sajeb Ads Demo',
                'advertiser_email'=> 'ads@example.com',
            ],
            'article_middle' => [
                'image_url'       => 'https://placehold.co/728x90/005e2c/ffffff?text=Article+Middle+Ad+728×90',
                'ad_url'          => 'https://example.com',
                'alt_text'        => 'আর্টিকেল মিডল বিজ্ঞাপন',
                'code'            => '',
                'advertiser_name' => 'Sajeb Ads Demo',
                'advertiser_email'=> 'ads@example.com',
            ],
            'article_conclusion' => [
                'image_url'       => 'https://placehold.co/728x90/B5460F/ffffff?text=Article+Conclusion+Ad+728×90',
                'ad_url'          => 'https://example.com',
                'alt_text'        => 'আর্টিকেল কনক্লুশন বিজ্ঞাপন',
                'code'            => '',
                'advertiser_name' => 'Sajeb Ads Demo',
                'advertiser_email'=> 'ads@example.com',
            ],
            'below_article' => [
                'image_url'       => 'https://placehold.co/970x90/1565C0/ffffff?text=Below+Article+Banner+970×90',
                'ad_url'          => 'https://example.com',
                'alt_text'        => 'আর্টিকেলের নিচে ব্যানার',
                'code'            => '',
                'advertiser_name' => 'Sajeb Ads Demo',
                'advertiser_email'=> 'ads@example.com',
            ],
            'footer_banner' => [
                'image_url'       => 'https://placehold.co/970x90/00796B/ffffff?text=Footer+Banner+970×90',
                'ad_url'          => 'https://example.com',
                'alt_text'        => 'ফুটার ব্যানার বিজ্ঞাপন',
                'code'            => '',
                'advertiser_name' => 'Sajeb Ads Demo',
                'advertiser_email'=> 'ads@example.com',
            ],
        ];

        $updated = 0;
        foreach ($dummyAds as $placement => $data) {
            $count = Advertisement::where('placement', $placement)
                ->where('is_active', true)
                ->update(array_merge($data, [
                    'is_active'   => true,
                    'start_date'  => now()->subDay(),
                    'end_date'    => null,
                ]));
            $updated += $count;
        }

        $this->command->info("✅ {$updated} টি সক্রিয় বিজ্ঞাপনে ডামি কন্টেন্ট যোগ করা হয়েছে!");
    }
}
