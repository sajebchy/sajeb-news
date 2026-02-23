<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PushNotificationController extends Controller
{
    /**
     * Subscribe user to push notifications
     */
    public function subscribe(Request $request)
    {
        try {
            $validated = $request->validate([
                'endpoint' => 'required|string|unique:push_subscriptions',
                'publicKey' => 'required|string',
                'authToken' => 'required|string',
            ]);

            $subscription = PushSubscription::firstOrCreate(
                ['endpoint' => $validated['endpoint']],
                [
                    'public_key' => $validated['publicKey'],
                    'auth_token' => $validated['authToken'],
                    'user_ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'is_active' => true,
                ]
            );

            // If subscription was previously inactive, reactivate it
            if (!$subscription->is_active) {
                $subscription->activate();
            }

            // Send to Feedify if enabled
            $this->syncToFeedify($request->ip());

            Log::info('Push subscription created', [
                'endpoint' => substr($validated['endpoint'], 0, 50) . '...',
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'সফলভাবে সাবস্ক্রাইব হয়েছেন! (Successfully subscribed!)',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'সাবস্ক্রিপশন বিফল হয়েছে। (Subscription failed.)',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Push subscription error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'একটি ত্রুটি ঘটেছে। (An error occurred.)',
            ], 500);
        }
    }

    /**
     * Sync subscriber to Feedify
     */
    private function syncToFeedify($userEmail = null)
    {
        try {
            $seoSettings = \App\Models\SeoSetting::first();
            
            // Check if Feedify is enabled and configured
            if (!$seoSettings->feedify_enabled || !$seoSettings->feedify_api_key || !$seoSettings->feedify_list_id) {
                return;
            }

            // Get subscriber email - either from auth user or pass as parameter
            $email = $userEmail ?? auth()->user()?->email ?? request()->ip() . '@subscriber.local';
            
            // Call Feedify API
            $response = $this->callFeedifyApi(
                'subscribers',
                'POST',
                [
                    'list_id' => $seoSettings->feedify_list_id,
                    'email' => $email,
                    'name' => auth()->user()?->name ?? 'Subscriber',
                    'tags' => ['push-notification', 'web-subscriber'],
                    'subscribe' => true,
                ]
            );

            Log::info('Feedify sync attempt', [
                'success' => $response['success'] ?? false,
                'email' => $email,
            ]);

        } catch (\Exception $e) {
            Log::warning('Feedify sync failed: ' . $e->getMessage());
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

    /**
     * Unsubscribe user from push notifications
     */
    public function unsubscribe(Request $request)
    {
        try {
            $validated = $request->validate([
                'endpoint' => 'required|string|exists:push_subscriptions',
            ]);

            $subscription = PushSubscription::where('endpoint', $validated['endpoint'])->first();
            
            if ($subscription) {
                $subscription->deactivate();
                
                Log::info('Push subscription deactivated', [
                    'endpoint' => substr($validated['endpoint'], 0, 50) . '...',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'সাবস্ক্রিপশন বাতিল করা হয়েছে। (Unsubscribed successfully.)',
            ]);
        } catch (\Exception $e) {
            Log::error('Push unsubscription error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'সাবস্ক্রিপশন বাতিল ব্যর্থ। (Unsubscription failed.)',
            ], 500);
        }
    }

    /**
     * Check if user is subscribed
     */
    public function checkSubscription(Request $request)
    {
        try {
            $validated = $request->validate([
                'endpoint' => 'required|string',
            ]);

            $subscription = PushSubscription::where('endpoint', $validated['endpoint'])
                ->where('is_active', true)
                ->first();

            return response()->json([
                'success' => true,
                'subscribed' => $subscription ? true : false,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'subscribed' => false,
            ], 500);
        }
    }

    /**
     * Get subscription statistics
     */
    public function getStats()
    {
        return response()->json([
            'total_subscriptions' => PushSubscription::count(),
            'active_subscriptions' => PushSubscription::where('is_active', true)->count(),
            'inactive_subscriptions' => PushSubscription::where('is_active', false)->count(),
        ]);
    }
}
