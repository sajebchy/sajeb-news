<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Services\SeoService;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = JobPost::published()
                ->where(function ($q) {
                    $q->whereNull('application_deadline')
                      ->orWhere('application_deadline', '>=', now()->toDateString());
                });

            if ($search = $request->input('q')) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('company_name', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%")
                      ->orWhere('skills', 'like', "%{$search}%");
                });
            }

            if ($sector = $request->input('sector')) {
                $query->where('job_sector', $sector);
            }

            if ($type = $request->input('type')) {
                $query->where('job_type', $type);
            }

            if ($division = $request->input('division')) {
                $query->where('division', $division);
            }

            $jobs = $query->latest('published_at')->paginate(12)->appends($request->only(['q', 'sector', 'type', 'division']));

            $featuredJobs = JobPost::featured()
                ->where(function ($q) {
                    $q->whereNull('application_deadline')
                      ->orWhere('application_deadline', '>=', now()->toDateString());
                })
                ->latest('published_at')
                ->limit(5)
                ->get();
        } catch (\Throwable $e) {
            $jobs = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);
            $featuredJobs = collect();
        }

        return view('public.jobs.index', compact('jobs', 'featuredJobs'));
    }

    public function show(JobPost $job_post)
    {
        $job_post->increment('views');

        $relatedJobs = JobPost::published()
            ->where('id', '!=', $job_post->id)
            ->where(function ($q) use ($job_post) {
                $q->where('job_sector', $job_post->job_sector)
                  ->orWhere('company_name', $job_post->company_name);
            })
            ->where(function ($q) {
                $q->whereNull('application_deadline')
                  ->orWhere('application_deadline', '>=', now()->toDateString());
            })
            ->latest('published_at')
            ->limit(4)
            ->get();

        return view('public.jobs.show', compact('job_post', 'relatedJobs'));
    }
}
