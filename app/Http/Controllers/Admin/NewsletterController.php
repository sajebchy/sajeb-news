<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Display a listing of newsletter subscribers.
     */
    public function index()
    {
        $subscribers = NewsletterSubscriber::latest()
            ->paginate(20);

        $totalSubscribers = NewsletterSubscriber::count();
        $verifiedSubscribers = NewsletterSubscriber::where('is_verified', true)->count();
        $unverifiedSubscribers = NewsletterSubscriber::where('is_verified', false)->count();

        return view('admin.newsletters.index', [
            'subscribers' => $subscribers,
            'totalSubscribers' => $totalSubscribers,
            'verifiedSubscribers' => $verifiedSubscribers,
            'unverifiedSubscribers' => $unverifiedSubscribers,
        ]);
    }

    /**
     * Delete a newsletter subscriber.
     */
    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();

        return redirect()->route('admin.newsletters.index')
            ->with('success', 'Newsletter subscriber deleted successfully.');
    }
}
