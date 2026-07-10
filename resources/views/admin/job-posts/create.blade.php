@extends('layouts.admin')

@section('page-title', isset($job) && $job->exists ? 'চাকরি সম্পাদনা' : 'নতুন চাকরি পোস্ট')

@section('content')
<div class="row">
    <div class="col-12 col-lg-10 offset-lg-1">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-briefcase"></i>
                    {{ isset($job) && $job->exists ? 'চাকরি পোস্ট সম্পাদনা' : 'নতুন চাকরি পোস্ট তৈরি করুন' }}
                </h5>

                @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST"
                      action="{{ isset($job) && $job->exists ? route('admin.job-posts.update', $job) : route('admin.job-posts.store') }}"
                      enctype="multipart/form-data">
                    @csrf
                    @if(isset($job) && $job->exists)
                        @method('PUT')
                    @endif

                    {{-- ===== Basic Info ===== --}}
                    <h6 class="mb-3"><i class="bi bi-info-circle"></i> মৌলিক তথ্য</h6>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="title" class="form-label">পদের নাম / চাকরির শিরোনাম *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                                   value="{{ old('title', $job->title ?? '') }}" required placeholder="যেমন: সিনিয়র সফটওয়্যার ইঞ্জিনিয়ার">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="company_name" class="form-label">প্রতিষ্ঠানের নাম *</label>
                            <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name"
                                   value="{{ old('company_name', $job->company_name ?? '') }}" required placeholder="যেমন: বাংলাদেশ ব্যাংক">
                            @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="job_sector" class="form-label">সেক্টর / খাত *</label>
                            <select class="form-control @error('job_sector') is-invalid @enderror" id="job_sector" name="job_sector" required>
                                @foreach(\App\Models\JobPost::jobSectors() as $sector)
                                <option value="{{ $sector }}" @selected(old('job_sector', $job->job_sector ?? '') === $sector)>{{ $sector }}</option>
                                @endforeach
                            </select>
                            @error('job_sector') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="job_type" class="form-label">চাকরির ধরন *</label>
                            <select class="form-control @error('job_type') is-invalid @enderror" id="job_type" name="job_type" required>
                                <option value="full-time" @selected(old('job_type', $job->job_type ?? '') === 'full-time')>পূর্ণকালীন</option>
                                <option value="part-time" @selected(old('job_type', $job->job_type ?? '') === 'part-time')>খণ্ডকালীন</option>
                                <option value="contract" @selected(old('job_type', $job->job_type ?? '') === 'contract')>চুক্তিভিত্তিক</option>
                                <option value="internship" @selected(old('job_type', $job->job_type ?? '') === 'internship')>ইন্টার্নশিপ</option>
                                <option value="freelance" @selected(old('job_type', $job->job_type ?? '') === 'freelance')>ফ্রিল্যান্স</option>
                            </select>
                            @error('job_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="workplace_type" class="form-label">কর্মস্থল *</label>
                            <select class="form-control @error('workplace_type') is-invalid @enderror" id="workplace_type" name="workplace_type" required>
                                <option value="onsite" @selected(old('workplace_type', $job->workplace_type ?? '') === 'onsite')>অফিসে</option>
                                <option value="remote" @selected(old('workplace_type', $job->workplace_type ?? '') === 'remote')>রিমোট</option>
                                <option value="hybrid" @selected(old('workplace_type', $job->workplace_type ?? '') === 'hybrid')>হাইব্রিড</option>
                            </select>
                            @error('workplace_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="company_logo" class="form-label">প্রতিষ্ঠানের লোগো</label>
                        @if(isset($job) && $job->company_logo)
                        <div class="mb-2"><img src="{{ asset('storage/' . $job->company_logo) }}" alt="Logo" style="max-height:60px;"></div>
                        @endif
                        <input type="file" class="form-control @error('company_logo') is-invalid @enderror" id="company_logo" name="company_logo" accept="image/*">
                        @error('company_logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- ===== Description ===== --}}
                    <hr class="my-4">
                    <h6 class="mb-3"><i class="bi bi-text-paragraph"></i> বিবরণ</h6>

                    <div class="mb-3">
                        <label for="description" class="form-label">চাকরির বিবরণ *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="6" required>{{ old('description', $job->description ?? '') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="responsibilities" class="form-label">দায়িত্বসমূহ</label>
                        <textarea class="form-control @error('responsibilities') is-invalid @enderror" id="responsibilities" name="responsibilities" rows="4" placeholder="প্রতিটি দায়িত্ব নতুন লাইনে লিখুন">{{ old('responsibilities', $job->responsibilities ?? '') }}</textarea>
                        @error('responsibilities') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="requirements" class="form-label">যোগ্যতা ও শর্তাবলী</label>
                        <textarea class="form-control @error('requirements') is-invalid @enderror" id="requirements" name="requirements" rows="4" placeholder="প্রতিটি শর্ত নতুন লাইনে লিখুন">{{ old('requirements', $job->requirements ?? '') }}</textarea>
                        @error('requirements') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="benefits" class="form-label">সুযোগ-সুবিধা</label>
                        <textarea class="form-control @error('benefits') is-invalid @enderror" id="benefits" name="benefits" rows="3" placeholder="বেতন ছাড়া অন্যান্য সুবিধা">{{ old('benefits', $job->benefits ?? '') }}</textarea>
                        @error('benefits') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- ===== Experience & Education ===== --}}
                    <hr class="my-4">
                    <h6 class="mb-3"><i class="bi bi-mortarboard"></i> অভিজ্ঞতা ও শিক্ষা</h6>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="experience_level" class="form-label">অভিজ্ঞতার স্তর</label>
                            <select class="form-control @error('experience_level') is-invalid @enderror" id="experience_level" name="experience_level">
                                <option value="">নির্বাচন করুন</option>
                                <option value="entry" @selected(old('experience_level', $job->experience_level ?? '') === 'entry')>প্রবেশ পর্যায়</option>
                                <option value="mid" @selected(old('experience_level', $job->experience_level ?? '') === 'mid')>মধ্যম পর্যায়</option>
                                <option value="senior" @selected(old('experience_level', $job->experience_level ?? '') === 'senior')>সিনিয়র</option>
                                <option value="lead" @selected(old('experience_level', $job->experience_level ?? '') === 'lead')>লিড/ম্যানেজার</option>
                            </select>
                            @error('experience_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="experience_min" class="form-label">সর্বনিম্ন অভিজ্ঞতা (বছর)</label>
                            <input type="number" class="form-control @error('experience_min') is-invalid @enderror" id="experience_min" name="experience_min"
                                   value="{{ old('experience_min', $job->experience_min ?? '') }}" min="0" max="50">
                            @error('experience_min') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="experience_max" class="form-label">সর্বোচ্চ অভিজ্ঞতা (বছর)</label>
                            <input type="number" class="form-control @error('experience_max') is-invalid @enderror" id="experience_max" name="experience_max"
                                   value="{{ old('experience_max', $job->experience_max ?? '') }}" min="0" max="50">
                            @error('experience_max') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="education" class="form-label">শিক্ষাগত যোগ্যতা</label>
                            <input type="text" class="form-control @error('education') is-invalid @enderror" id="education" name="education"
                                   value="{{ old('education', $job->education ?? '') }}" placeholder="যেমন: স্নাতক (সম্মান) — CSE/EEE">
                            @error('education') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="skills" class="form-label">দক্ষতা / Skills</label>
                            <input type="text" class="form-control @error('skills') is-invalid @enderror" id="skills" name="skills"
                                   value="{{ old('skills', $job->skills ?? '') }}" placeholder="কমা দিয়ে আলাদা করুন: PHP, Laravel, React">
                            @error('skills') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- ===== Salary ===== --}}
                    <hr class="my-4">
                    <h6 class="mb-3"><i class="bi bi-currency-dollar"></i> বেতন</h6>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="salary_min" class="form-label">সর্বনিম্ন বেতন (৳)</label>
                            <input type="number" class="form-control @error('salary_min') is-invalid @enderror" id="salary_min" name="salary_min"
                                   value="{{ old('salary_min', $job->salary_min ?? '') }}" min="0">
                            @error('salary_min') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="salary_max" class="form-label">সর্বোচ্চ বেতন (৳)</label>
                            <input type="number" class="form-control @error('salary_max') is-invalid @enderror" id="salary_max" name="salary_max"
                                   value="{{ old('salary_max', $job->salary_max ?? '') }}" min="0">
                            @error('salary_max') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="salary_period" class="form-label">বেতনের মেয়াদ</label>
                            <select class="form-control @error('salary_period') is-invalid @enderror" id="salary_period" name="salary_period">
                                <option value="monthly" @selected(old('salary_period', $job->salary_period ?? 'monthly') === 'monthly')>মাসিক</option>
                                <option value="yearly" @selected(old('salary_period', $job->salary_period ?? '') === 'yearly')>বার্ষিক</option>
                            </select>
                            @error('salary_period') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3 mb-3 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="is_salary_negotiable" name="is_salary_negotiable" value="1"
                                       {{ old('is_salary_negotiable', $job->is_salary_negotiable ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_salary_negotiable">আলোচনা সাপেক্ষে</label>
                            </div>
                        </div>
                    </div>

                    {{-- ===== Location (GEO) ===== --}}
                    <hr class="my-4">
                    <h6 class="mb-3"><i class="bi bi-geo-alt"></i> অবস্থান (GEO)</h6>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="location" class="form-label">ঠিকানা / এলাকা</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location"
                                   value="{{ old('location', $job->location ?? '') }}" placeholder="যেমন: মতিঝিল, ঢাকা">
                            @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="district" class="form-label">জেলা</label>
                            <input type="text" class="form-control @error('district') is-invalid @enderror" id="district" name="district"
                                   value="{{ old('district', $job->district ?? '') }}" placeholder="যেমন: ঢাকা">
                            @error('district') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="division" class="form-label">বিভাগ</label>
                            <select class="form-control @error('division') is-invalid @enderror" id="division" name="division">
                                <option value="">নির্বাচন করুন</option>
                                @foreach(\App\Models\JobPost::divisions() as $div)
                                <option value="{{ $div }}" @selected(old('division', $job->division ?? '') === $div)>{{ $div }}</option>
                                @endforeach
                            </select>
                            @error('division') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="country" class="form-label">দেশ</label>
                            <input type="text" class="form-control" id="country" name="country" value="{{ old('country', $job->country ?? 'বাংলাদেশ') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="latitude" class="form-label">অক্ষাংশ (Latitude)</label>
                            <input type="number" step="0.0000001" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude"
                                   value="{{ old('latitude', $job->latitude ?? '') }}" placeholder="23.8103">
                            @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="longitude" class="form-label">দ্রাঘিমাংশ (Longitude)</label>
                            <input type="number" step="0.0000001" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude"
                                   value="{{ old('longitude', $job->longitude ?? '') }}" placeholder="90.4125">
                            @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- ===== Application ===== --}}
                    <hr class="my-4">
                    <h6 class="mb-3"><i class="bi bi-send"></i> আবেদনের তথ্য</h6>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="vacancy_count" class="form-label">পদ সংখ্যা</label>
                            <input type="number" class="form-control @error('vacancy_count') is-invalid @enderror" id="vacancy_count" name="vacancy_count"
                                   value="{{ old('vacancy_count', $job->vacancy_count ?? '') }}" min="1">
                            @error('vacancy_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="application_deadline" class="form-label">আবেদনের শেষ তারিখ</label>
                            <input type="date" class="form-control @error('application_deadline') is-invalid @enderror" id="application_deadline" name="application_deadline"
                                   value="{{ old('application_deadline', isset($job) && $job->application_deadline ? $job->application_deadline->format('Y-m-d') : '') }}">
                            @error('application_deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="application_email" class="form-label">আবেদনের ইমেইল</label>
                            <input type="email" class="form-control @error('application_email') is-invalid @enderror" id="application_email" name="application_email"
                                   value="{{ old('application_email', $job->application_email ?? '') }}">
                            @error('application_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="application_url" class="form-label">আবেদনের লিংক (URL)</label>
                        <input type="url" class="form-control @error('application_url') is-invalid @enderror" id="application_url" name="application_url"
                               value="{{ old('application_url', $job->application_url ?? '') }}" placeholder="https://example.com/apply">
                        @error('application_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- ===== Publish Settings ===== --}}
                    <hr class="my-4">
                    <h6 class="mb-3"><i class="bi bi-gear"></i> প্রকাশনা সেটিংস</h6>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label">স্ট্যাটাস *</label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="draft" @selected(old('status', $job->status ?? 'draft') === 'draft')>ড্রাফট</option>
                                <option value="published" @selected(old('status', $job->status ?? '') === 'published')>প্রকাশিত</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3 d-flex align-items-end gap-4">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1"
                                       {{ old('is_featured', $job->is_featured ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">ফিচার্ড</label>
                            </div>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="is_urgent" name="is_urgent" value="1"
                                       {{ old('is_urgent', $job->is_urgent ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_urgent">জরুরি</label>
                            </div>
                        </div>
                    </div>

                    {{-- ===== SEO ===== --}}
                    <hr class="my-4">
                    <h6 class="mb-3"><i class="bi bi-search"></i> SEO সেটিংস (ঐচ্ছিক)</h6>

                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" name="meta_title"
                               value="{{ old('meta_title', $job->meta_title ?? '') }}" placeholder="স্বয়ংক্রিয় তৈরি হবে খালি রাখলে">
                        @error('meta_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="2"
                                  placeholder="স্বয়ংক্রিয় তৈরি হবে খালি রাখলে">{{ old('meta_description', $job->meta_description ?? '') }}</textarea>
                        @error('meta_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                        <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" id="meta_keywords" name="meta_keywords"
                               value="{{ old('meta_keywords', $job->meta_keywords ?? '') }}" placeholder="চাকরি, নিয়োগ, বাংলাদেশ">
                        @error('meta_keywords') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="og_image" class="form-label">OG Image</label>
                        <input type="file" class="form-control @error('og_image') is-invalid @enderror" id="og_image" name="og_image" accept="image/*">
                        @error('og_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- ===== Submit ===== --}}
                    <hr class="my-4">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check"></i> {{ isset($job) && $job->exists ? 'আপডেট করুন' : 'প্রকাশ করুন' }}
                        </button>
                        <a href="{{ route('admin.job-posts.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x"></i> বাতিল
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
