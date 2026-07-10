<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Services\ImageOptimizer;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $query = JobPost::with('author');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($sector = $request->input('sector')) {
            $query->where('job_sector', $sector);
        }

        $jobs = $query->latest()->paginate(15)->appends($request->only(['search', 'status', 'sector']));

        $stats = [
            'total' => JobPost::count(),
            'published' => JobPost::where('status', 'published')->count(),
            'draft' => JobPost::where('status', 'draft')->count(),
            'expired' => JobPost::where('status', 'published')
                ->where('application_deadline', '<', now()->toDateString())->count(),
        ];

        return view('admin.job-posts.index', compact('jobs', 'stats'));
    }

    public function create()
    {
        return view('admin.job-posts.create', ['job' => new JobPost()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());

        $validated['author_id'] = auth()->id();

        if ($request->hasFile('company_logo')) {
            $validated['company_logo'] = (new ImageOptimizer)->optimize(
                $request->file('company_logo'), 'logo', 'job-logos'
            );
        }

        if ($request->hasFile('og_image')) {
            $validated['og_image'] = (new ImageOptimizer)->optimize(
                $request->file('og_image'), 'og_image', 'job-og'
            );
        }

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_urgent'] = $request->boolean('is_urgent');
        $validated['is_salary_negotiable'] = $request->boolean('is_salary_negotiable');

        $job = JobPost::create($validated);

        $this->logActivity('created', 'JobPost', $job->id);

        return redirect()->route('admin.job-posts.index')
            ->with('success', 'চাকরি পোস্ট সফলভাবে তৈরি হয়েছে।');
    }

    public function edit(JobPost $job_post)
    {
        return view('admin.job-posts.create', ['job' => $job_post]);
    }

    public function update(Request $request, JobPost $job_post)
    {
        $validated = $request->validate($this->validationRules($job_post->id));

        if ($request->hasFile('company_logo')) {
            $validated['company_logo'] = (new ImageOptimizer)->optimize(
                $request->file('company_logo'), 'logo', 'job-logos'
            );
        }

        if ($request->hasFile('og_image')) {
            $validated['og_image'] = (new ImageOptimizer)->optimize(
                $request->file('og_image'), 'og_image', 'job-og'
            );
        }

        if ($validated['status'] === 'published' && !$job_post->published_at) {
            $validated['published_at'] = now();
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_urgent'] = $request->boolean('is_urgent');
        $validated['is_salary_negotiable'] = $request->boolean('is_salary_negotiable');

        $job_post->update($validated);

        $this->logActivity('updated', 'JobPost', $job_post->id);

        return redirect()->route('admin.job-posts.index')
            ->with('success', 'চাকরি পোস্ট আপডেট হয়েছে।');
    }

    public function destroy(JobPost $job_post)
    {
        $this->logActivity('deleted', 'JobPost', $job_post->id);
        $job_post->delete();

        return redirect()->route('admin.job-posts.index')
            ->with('success', 'চাকরি পোস্ট মুছে ফেলা হয়েছে।');
    }

    private function validationRules($id = null): array
    {
        return [
            'title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_logo' => 'nullable|image|max:1024',
            'description' => 'required|string',
            'responsibilities' => 'nullable|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'job_sector' => 'required|string|max:100',
            'job_type' => 'required|in:full-time,part-time,contract,internship,freelance',
            'workplace_type' => 'required|in:onsite,remote,hybrid',
            'experience_level' => 'nullable|in:entry,mid,senior,lead',
            'experience_min' => 'nullable|integer|min:0|max:50',
            'experience_max' => 'nullable|integer|min:0|max:50',
            'salary_min' => 'nullable|integer|min:0',
            'salary_max' => 'nullable|integer|min:0',
            'salary_currency' => 'nullable|string|max:10',
            'salary_period' => 'nullable|in:monthly,yearly',
            'is_salary_negotiable' => 'nullable',
            'location' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:100',
            'division' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'vacancy_count' => 'nullable|integer|min:1',
            'education' => 'nullable|string|max:255',
            'skills' => 'nullable|string',
            'application_url' => 'nullable|url|max:500',
            'application_email' => 'nullable|email|max:255',
            'application_deadline' => 'nullable|date',
            'published_at' => 'nullable|date',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable',
            'is_urgent' => 'nullable',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url|max:500',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|image|max:1024',
        ];
    }
}
