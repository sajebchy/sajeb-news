<?php

namespace App\Http\Controllers;

use App\Models\LiveStream;
use App\Models\StreamComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StreamCommentController extends Controller
{
    /**
     * Store a newly created comment
     */
    public function store(Request $request, LiveStream $stream)
    {
        $validator = Validator::make($request->all(), [
            'comment_text' => 'required|string|min:1|max:500',
            'commenter_name' => 'required|string|max:100',
            'commenter_email' => 'nullable|email|max:100',
            'facebook_id' => 'nullable|string|max:100',
            'commenter_avatar' => 'nullable|url',
            'source' => 'in:facebook,website,anonymous',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Check if comment already exists (prevent duplicates)
            if ($request->facebook_id) {
                $existing = StreamComment::where('live_stream_id', $stream->id)
                    ->where('facebook_id', $request->facebook_id)
                    ->where('comment_text', $request->comment_text)
                    ->where('created_at', '>', now()->subMinutes(1))
                    ->first();

                if ($existing) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Duplicate comment detected. Please wait before posting again.',
                    ], 409);
                }
            }

            $comment = StreamComment::create([
                'live_stream_id' => $stream->id,
                'commenter_name' => $request->commenter_name,
                'commenter_email' => $request->commenter_email,
                'facebook_id' => $request->facebook_id,
                'facebook_profile_url' => $request->get('facebook_profile_url'),
                'commenter_avatar' => $request->commenter_avatar,
                'comment_text' => $request->comment_text,
                'source' => $request->get('source', 'website'),
                'is_verified' => !empty($request->facebook_id),
                'is_approved' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comment posted successfully!',
                'data' => [
                    'id' => $comment->id,
                    'name' => $comment->commenter_name,
                    'avatar' => $comment->commenter_avatar,
                    'text' => $comment->comment_text,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'is_verified' => $comment->is_verified,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to post comment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get comments for a live stream
     */
    public function getComments(LiveStream $stream)
    {
        try {
            $comments = StreamComment::where('live_stream_id', $stream->id)
                ->approved()
                ->orderByRaw('is_pinned DESC')
                ->orderBy('created_at', 'desc')
                ->select('id', 'commenter_name', 'commenter_avatar', 'comment_text', 'is_verified', 'is_pinned', 'created_at', 'likes_count')
                ->get()
                ->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'name' => $comment->commenter_name,
                        'avatar' => $comment->commenter_avatar,
                        'text' => $comment->comment_text,
                        'is_verified' => $comment->is_verified,
                        'is_pinned' => $comment->is_pinned,
                        'likes' => $comment->likes_count,
                        'created_at' => $comment->created_at->diffForHumans(),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $comments,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch comments: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a comment (owner only)
     */
    public function destroy(StreamComment $comment)
    {
        try {
            // Users can only delete their own comments by email
            // Facebook users can only delete their own comments by facebook_id
            $isOwner = false;

            if (Auth::check()) {
                $user = Auth::user();
                if ($user->email === $comment->commenter_email) {
                    $isOwner = true;
                }
            }

            // Check if admin
            $isAdmin = Auth::check() && Auth::user()->hasRole('admin');

            if (!$isOwner && !$isAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this comment',
                ], 403);
            }

            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete comment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Admin: Approve a comment
     */
    public function approve(StreamComment $comment)
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        try {
            $comment->approve();

            return response()->json([
                'success' => true,
                'message' => 'Comment approved successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve comment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Admin: Reject a comment
     */
    public function reject(StreamComment $comment, Request $request)
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        try {
            $comment->reject();

            if ($request->admin_notes) {
                $comment->update(['admin_notes' => $request->admin_notes]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Comment rejected successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject comment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Admin: Pin a comment
     */
    public function pin(StreamComment $comment)
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        try {
            $comment->pin();

            return response()->json([
                'success' => true,
                'message' => 'Comment pinned successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to pin comment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Admin: Unpin a comment
     */
    public function unpin(StreamComment $comment)
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        try {
            $comment->unpin();

            return response()->json([
                'success' => true,
                'message' => 'Comment unpinned successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unpin comment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Like a comment
     */
    public function like(StreamComment $comment)
    {
        try {
            $comment->incrementLikes();

            return response()->json([
                'success' => true,
                'likes' => $comment->likes_count,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to like comment: ' . $e->getMessage(),
            ], 500);
        }
    }
}
