<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function vote(Request $request, Poll $poll)
    {
        $request->validate(['option_id' => 'required|integer|exists:poll_options,id']);

        $optionId = $request->input('option_id');
        $cookieKey = 'poll_voted_' . $poll->id;

        // Prevent duplicate votes via cookie
        if ($request->cookie($cookieKey)) {
            $poll->load('options');
            return response()->json([
                'success'   => false,
                'message'   => 'আপনি আগেই ভোট দিয়েছেন।',
                'results'   => $this->buildResults($poll),
            ]);
        }

        $option = PollOption::where('id', $optionId)
            ->where('poll_id', $poll->id)
            ->firstOrFail();

        $option->increment('votes');
        $poll->load('options');

        return response()->json([
            'success' => true,
            'message' => 'ভোট গৃহীত হয়েছে!',
            'results' => $this->buildResults($poll),
        ])->cookie($cookieKey, '1', 60 * 24 * 30); // 30 days
    }

    private function buildResults(Poll $poll): array
    {
        $total = $poll->options->sum('votes') ?: 1;

        return $poll->options->map(fn($opt) => [
            'id'      => $opt->id,
            'text'    => $opt->option_text,
            'votes'   => $opt->votes,
            'percent' => round(($opt->votes / $total) * 100),
        ])->toArray();
    }
}
