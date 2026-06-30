<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage
     */
    public function store(Request $request, News $news)
    {
        try {
            // Authenticate user
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'মন্তব্য করতে হলে প্রথমে লগইন করুন');
            }

            // Validate comment
            $validated = $request->validate([
                'comment_text' => 'required|string|min:3|max:1000|not_regex:/^[\s]*$/',
            ], [
                'comment_text.required' => 'মন্তব্য লিখা আবশ্যক',
                'comment_text.min' => 'মন্তব্য কমপক্ষে ৩ অক্ষর হতে হবে',
                'comment_text.max' => 'মন্তব্য ১০০০ অক্ষরের বেশি হতে পারবে না',
                'comment_text.not_regex' => 'মন্তব্য শুধু স্পেস হতে পারবে না',
            ]);

            // Create comment
            $comment = new Comment();
            $comment->news_id = $news->id;
            $comment->user_id = auth()->id();
            $comment->facebook_user_id = auth()->user()->facebook_user_id ?? null;
            $comment->comment_text = trim($validated['comment_text']);
            $comment->approved = true;
            $comment->save();

            return redirect()->route('news.show', $news->slug)
                ->with('comment_success', '✓ আপনার মন্তব্য সফলভাবে প্রকাশিত হয়েছে!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Comment creation error', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'news_id' => $news->id ?? null,
            ]);

            return redirect()->route('news.show', $news->slug)
                ->with('comment_error', '⚠️ মন্তব্য প্রকাশে ত্রুটি হয়েছে। পরে আবার চেষ্টা করুন।');
        }
    }

    /**
     * Delete a comment
     */
    public function destroy(Comment $comment)
    {
        try {
            // Check authorization
            if (auth()->id() !== $comment->user_id && !auth()->user()->isAdmin()) {
                return response()->json(['error' => 'অননুমোদিত'], 403);
            }

            $news = $comment->news;
            $comment->delete();

            Log::info('Comment deleted', [
                'comment_id' => $comment->id,
                'deleted_by' => auth()->id(),
            ]);

            return redirect()->route('news.show', $news->slug)
                ->with('comment_success', 'মন্তব্য সফলভাবে মুছে ফেলা হয়েছে।');

        } catch (\Exception $e) {
            Log::error('Comment deletion error', [
                'error' => $e->getMessage(),
                'comment_id' => $comment->id,
            ]);

            return back()->with('comment_error', 'মন্তব্য মুছতে ত্রুটি হয়েছে।');
        }
    }

    /**
     * Approve a comment (Admin only)
     */
    public function approve(Comment $comment)
    {
        try {
            // Check authorization
            $this->authorize('approve', $comment);

            $comment->update(['approved' => true]);

            Log::info('Comment approved', [
                'comment_id' => $comment->id,
                'approved_by' => auth()->id(),
            ]);

            return back()->with('success', 'মন্তব্য অনুমোদিত হয়েছে।');

        } catch (\Exception $e) {
            Log::error('Comment approval error', [
                'error' => $e->getMessage(),
                'comment_id' => $comment->id,
            ]);

            return back()->with('error', 'মন্তব্য অনুমোদনে ত্রুটি হয়েছে।');
        }
    }

    /**
     * Reject a comment (Admin only)
     */
    public function reject(Comment $comment)
    {
        try {
            // Check authorization
            $this->authorize('reject', $comment);

            $comment->update(['approved' => false]);

            Log::info('Comment rejected', [
                'comment_id' => $comment->id,
                'rejected_by' => auth()->id(),
            ]);

            return back()->with('success', 'মন্তব্য প্রত্যাখ্যান করা হয়েছে।');

        } catch (\Exception $e) {
            Log::error('Comment rejection error', [
                'error' => $e->getMessage(),
                'comment_id' => $comment->id,
            ]);

            return back()->with('error', 'মন্তব্য প্রত্যাখ্যানে ত্রুটি হয়েছে।');
        }
    }
}
