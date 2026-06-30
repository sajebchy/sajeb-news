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
