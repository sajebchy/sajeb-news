<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReaderProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $comments = \App\Models\Comment::where('user_id', $user->id)
            ->with('news:id,title,slug')
            ->latest()
            ->paginate(15);

        return view('public.reader-profile', compact('user', 'comments'));
    }
}
