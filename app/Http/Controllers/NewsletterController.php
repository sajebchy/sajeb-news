<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletter_subscribers,email',
            'name' => 'nullable|string|max:255',
        ]);

        try {
            NewsletterSubscriber::create([
                'email' => $validated['email'],
                'name' => $validated['name'] ?? null,
                'is_verified' => true,
                'subscribed_at' => now(),
            ]);

            // Sync to Feedify
            $this->syncToFeedify($validated['email'], $validated['name'] ?? 'Subscriber');

            return back()->with('success', 'সফলভাবে নিউজলেটার সাবস্ক্রাইব করেছেন। ধন্যবাদ!');
        } catch (\Exception $e) {
            return back()->with('error', 'সাবস্ক্রিপশন ব্যর্থ হয়েছে। পরে চেষ্টা করুন।');
        }
    }

    /**
     * Sync subscriber to Feedify
     */
    private function syncToFeedify($email, $name = 'Subscriber')
    {
        try {
            $seoSettings = \App\Models\SeoSetting::first();
            
            // Check if Feedify is enabled and configured
            if (!$seoSettings->feedify_enabled || !$seoSettings->feedify_api_key || !$seoSettings->feedify_list_id) {
                return;
            }

            // Call Feedify API
            $response = $this->callFeedifyApi(
                'subscribers',
                'POST',
                [
                    'list_id' => $seoSettings->feedify_list_id,
                    'email' => $email,
                    'name' => $name,
                    'tags' => ['newsletter-subscriber', 'web-subscriber'],
                    'subscribe' => true,
                ]
            );

            Log::info('Feedify newsletter sync attempted', [
                'success' => $response['success'] ?? false,
                'email' => $email,
            ]);

        } catch (\Exception $e) {
            Log::warning('Feedify newsletter sync failed: ' . $e->getMessage());
            // Don't throw - subscription should succeed even if Feedify fails
        }
    }

    /**
     * Call Feedify API
     */
    private function callFeedifyApi($endpoint, $method = 'GET', $data = null)
    {
        $seoSettings = \App\Models\SeoSetting::first();
        
        $url = 'https://api.feedify.io/v1/' . $endpoint;
        
        $headers = [
            'Authorization' => 'Bearer ' . $seoSettings->feedify_api_key,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders($headers)
                ->timeout(30)
                ->{strtolower($method)}($url, $data ?? []);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::warning('Feedify API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => $response->body(),
            ];

        } catch (\Exception $e) {
            Log::error('Feedify API request failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
