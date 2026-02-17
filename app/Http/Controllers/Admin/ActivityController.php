<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display activity logs with filters and search.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Search by user name or email
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by action type
        if ($request->filled('action')) {
            $query->where('action', $request->get('action'));
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->get('model_type'));
        }

        $activities = $query->paginate(30);

        return view('admin.activities.index', [
            'activities' => $activities,
        ]);
    }
}
