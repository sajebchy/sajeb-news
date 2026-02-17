<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

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

            return back()->with('success', 'সফলভাবে নিউজলেটার সাবস্ক্রাইব করেছেন। ধন্যবাদ!');
        } catch (\Exception $e) {
            return back()->with('error', 'সাবস্ক্রিপশন ব্যর্থ হয়েছে। পরে চেষ্টা করুন।');
        }
    }
}
