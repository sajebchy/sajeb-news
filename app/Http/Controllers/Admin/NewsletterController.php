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
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status === 'verified') {
            $query->where('is_verified', true)->whereNull('unsubscribed_at');
        } elseif ($request->status === 'unverified') {
            $query->where('is_verified', false);
        } elseif ($request->status === 'unsubscribed') {
            $query->whereNotNull('unsubscribed_at');
        }

        $subscribers          = $query->paginate(20)->withQueryString();
        $totalSubscribers     = NewsletterSubscriber::count();
        $verifiedSubscribers  = NewsletterSubscriber::where('is_verified', true)->whereNull('unsubscribed_at')->count();
        $unsubscribed         = NewsletterSubscriber::whereNotNull('unsubscribed_at')->count();
        $todaySubscribers     = NewsletterSubscriber::whereDate('created_at', today())->count();

        return view('admin.newsletters.index', compact(
            'subscribers', 'totalSubscribers', 'verifiedSubscribers', 'unsubscribed', 'todaySubscribers'
        ));
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
