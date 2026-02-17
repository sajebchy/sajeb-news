@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-gear"></i> Site Settings
        </h2>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validation Error!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basicSettings" type="button" role="tab" aria-controls="basicSettings" aria-selected="true">
                <i class="bi bi-info-circle"></i> Basic Settings
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="logos-tab" data-bs-toggle="tab" data-bs-target="#logosSettings" type="button" role="tab" aria-controls="logosSettings" aria-selected="false">
                <i class="bi bi-image"></i> Logos & Images
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analyticsSettings" type="button" role="tab" aria-controls="analyticsSettings" aria-selected="false">
                <i class="bi bi-graph-up"></i> Analytics
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#socialSettings" type="button" role="tab" aria-controls="socialSettings" aria-selected="false">
                <i class="bi bi-share"></i> Social Media
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="schema-tab" data-bs-toggle="tab" data-bs-target="#schemaSettings" type="button" role="tab" aria-controls="schemaSettings" aria-selected="false">
                <i class="bi bi-code-square"></i> JSON-LD Schema
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="push-tab" data-bs-toggle="tab" data-bs-target="#pushSettings" type="button" role="tab" aria-controls="pushSettings" aria-selected="false">
                <i class="bi bi-bell"></i> Push Notifications
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="settingsTabContent">
        <!-- Basic Settings Tab -->
        <div class="tab-pane fade show active" id="basicSettings" role="tabpanel" aria-labelledby="basic-tab">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label for="site_name" class="form-label">Site Name</label>
                    <input type="text" class="form-control @error('site_name') is-invalid @enderror" id="site_name" name="site_name" value="{{ old('site_name', $seoSettings->site_name ?? 'Sajeb NEWS') }}" required>
                    @error('site_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="site_url" class="form-label">Site URL</label>
                    <input type="url" class="form-control @error('site_url') is-invalid @enderror" id="site_url" name="site_url" value="{{ old('site_url', $seoSettings->site_url ?? config('app.url')) }}">
                    @error('site_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="site_title" class="form-label">Site Title (SEO)</label>
                    <input type="text" class="form-control @error('site_title') is-invalid @enderror" id="site_title" name="site_title" value="{{ old('site_title', $seoSettings->site_title ?? '') }}">
                    <small class="text-muted">Used in browser tabs and search results</small>
                    @error('site_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="site_description" class="form-label">Site Description</label>
                    <textarea class="form-control @error('site_description') is-invalid @enderror" id="site_description" name="site_description" rows="3" maxlength="500">{{ old('site_description', $seoSettings->site_description ?? '') }}</textarea>
                    <small class="text-muted">Max 500 characters - This is your meta description</small>
                    @error('site_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="meta_keywords" class="form-label">Meta Keywords (comma-separated)</label>
                    <textarea class="form-control @error('meta_keywords') is-invalid @enderror" id="meta_keywords" name="meta_keywords" rows="2" maxlength="500">{{ old('meta_keywords', $seoSettings->meta_keywords ?? '') }}</textarea>
                    <small class="text-muted">Comma-separated keywords for SEO</small>
                    @error('meta_keywords')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Logos & Images Tab -->
        <div class="tab-pane fade" id="logosSettings" role="tabpanel" aria-labelledby="logos-tab">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="row g-4">
                @csrf

                <!-- Desktop Logo -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-primary bg-opacity-10">
                            <h6 class="mb-0"><i class="bi bi-display"></i> Desktop Logo</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-2">Recommended: 250x60px</p>
                            <div class="mb-3">
                                <label for="logo" class="form-label">Upload Logo</label>
                                <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                                <small class="text-muted d-block mt-1">Max 5MB (JPG, PNG, SVG)</small>
                                @error('logo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($seoSettings && $seoSettings->logo)
                                <div class="preview-container">
                                    <label class="form-label small">Current Logo:</label>
                                    <div class="border rounded p-2" style="background: #f8f9fa;">
                                        <img src="{{ Storage::url($seoSettings->logo) }}" alt="Desktop Logo" style="max-width: 100%; max-height: 100px;">
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info alert-sm mb-0">
                                    <small><i class="bi bi-info-circle"></i> No logo uploaded</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Mobile Logo -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-success bg-opacity-10">
                            <h6 class="mb-0"><i class="bi bi-phone"></i> Mobile Logo</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-2">Recommended: 150x50px</p>
                            <div class="mb-3">
                                <label for="mobile_logo" class="form-label">Upload Logo</label>
                                <input type="file" class="form-control @error('mobile_logo') is-invalid @enderror" id="mobile_logo" name="mobile_logo" accept="image/*">
                                <small class="text-muted d-block mt-1">Max 5MB (JPG, PNG, SVG)</small>
                                @error('mobile_logo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($seoSettings && $seoSettings->mobile_logo)
                                <div class="preview-container">
                                    <label class="form-label small">Current Logo:</label>
                                    <div class="border rounded p-2" style="background: #f8f9fa;">
                                        <img src="{{ Storage::url($seoSettings->mobile_logo) }}" alt="Mobile Logo" style="max-width: 100%; max-height: 80px;">
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info alert-sm mb-0">
                                    <small><i class="bi bi-info-circle"></i> No logo uploaded</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- OG Image -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-warning bg-opacity-10">
                            <h6 class="mb-0"><i class="bi bi-share-fill"></i> Open Graph Image</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-2">Recommended: 1200x630px</p>
                            <div class="mb-3">
                                <label for="og_image" class="form-label">Upload Image</label>
                                <input type="file" class="form-control @error('og_image') is-invalid @enderror" id="og_image" name="og_image" accept="image/*">
                                <small class="text-muted d-block mt-1">For social media sharing (Facebook, Twitter, etc.)</small>
                                @error('og_image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($seoSettings && $seoSettings->og_image)
                                <div class="preview-container">
                                    <label class="form-label small">Current Image:</label>
                                    <div class="border rounded p-2" style="background: #f8f9fa;">
                                        <img src="{{ Storage::url($seoSettings->og_image) }}" alt="OG Image" style="max-width: 100%; max-height: 150px;">
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info alert-sm mb-0">
                                    <small><i class="bi bi-info-circle"></i> No image uploaded</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Favicon -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-danger bg-opacity-10">
                            <h6 class="mb-0"><i class="bi bi-star"></i> Favicon</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-2">Recommended: 32x32px</p>
                            <div class="mb-3">
                                <label for="favicon" class="form-label">Upload Favicon</label>
                                <input type="file" class="form-control @error('favicon') is-invalid @enderror" id="favicon" name="favicon" accept="image/x-icon,image/png">
                                <small class="text-muted d-block mt-1">Max 1MB (ICO, PNG)</small>
                                @error('favicon')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($seoSettings && $seoSettings->favicon)
                                <div class="preview-container">
                                    <label class="form-label small">Current Favicon:</label>
                                    <div class="border rounded p-2" style="background: #f8f9fa;">
                                        <img src="{{ Storage::url($seoSettings->favicon) }}" alt="Favicon" style="max-width: 100%; max-height: 50px;">
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info alert-sm mb-0">
                                    <small><i class="bi bi-info-circle"></i> Using default favicon</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Save Images
                    </button>
                </div>
            </form>
        </div>

        <!-- Analytics Tab -->
        <div class="tab-pane fade" id="analyticsSettings" role="tabpanel" aria-labelledby="analytics-tab">
            <form action="{{ route('admin.settings.update') }}" method="POST" class="row g-3">
                @csrf

                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Add your analytics tracking codes below
                    </div>
                </div>

                <div class="col-12">
                    <label for="google_analytics_id" class="form-label">Google Analytics ID (Legacy)</label>
                    <input type="text" class="form-control @error('google_analytics_id') is-invalid @enderror" id="google_analytics_id" name="google_analytics_id" placeholder="UA-XXXXXXXXX-X" value="{{ old('google_analytics_id', $seoSettings->google_analytics_id ?? '') }}">
                    <small class="text-muted">Format: UA-XXXXXXXXX-X (Deprecated)</small>
                    @error('google_analytics_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="ga_tracking_id" class="form-label">Google Analytics 4 ID</label>
                    <input type="text" class="form-control @error('ga_tracking_id') is-invalid @enderror" id="ga_tracking_id" name="ga_tracking_id" placeholder="G-XXXXXXXXXX" value="{{ old('ga_tracking_id', $seoSettings->ga_tracking_id ?? '') }}">
                    <small class="text-muted">Format: G-XXXXXXXXXX</small>
                    @error('ga_tracking_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="google_tag_manager_id" class="form-label">Google Tag Manager ID</label>
                    <input type="text" class="form-control @error('google_tag_manager_id') is-invalid @enderror" id="google_tag_manager_id" name="google_tag_manager_id" placeholder="GTM-XXXXXXX" value="{{ old('google_tag_manager_id', $seoSettings->google_tag_manager_id ?? '') }}">
                    <small class="text-muted">Format: GTM-XXXXXXX</small>
                    @error('google_tag_manager_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="gtm_tracking_id" class="form-label">Google Tag Manager Tracking ID</label>
                    <input type="text" class="form-control @error('gtm_tracking_id') is-invalid @enderror" id="gtm_tracking_id" name="gtm_tracking_id" placeholder="GTM-XXXXXXX" value="{{ old('gtm_tracking_id', $seoSettings->gtm_tracking_id ?? '') }}">
                    <small class="text-muted">Alternative GTM ID field</small>
                    @error('gtm_tracking_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="facebook_pixel_id" class="form-label">Facebook Pixel ID</label>
                    <input type="text" class="form-control @error('facebook_pixel_id') is-invalid @enderror" id="facebook_pixel_id" name="facebook_pixel_id" placeholder="1234567890" value="{{ old('facebook_pixel_id', $seoSettings->facebook_pixel_id ?? '') }}">
                    <small class="text-muted">Your Facebook Pixel ID</small>
                    @error('facebook_pixel_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="enable_analytics" class="form-check-label">
                        <input type="checkbox" class="form-check-input" id="enable_analytics" name="enable_analytics" value="1" {{ old('enable_analytics', $seoSettings->enable_analytics ?? false) ? 'checked' : '' }}>
                        Enable Analytics Tracking
                    </label>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Save Analytics
                    </button>
                </div>
            </form>
        </div>

        <!-- Social Media Tab -->
        <div class="tab-pane fade" id="socialSettings" role="tabpanel" aria-labelledby="social-tab">
            <form action="{{ route('admin.settings.update') }}" method="POST" class="row g-3">
                @csrf

                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Add your social media links
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="facebook_url" class="form-label"><i class="bi bi-facebook"></i> Facebook URL</label>
                    <input type="url" class="form-control @error('facebook_url') is-invalid @enderror" id="facebook_url" name="facebook_url" placeholder="https://facebook.com/yourpage" value="{{ old('facebook_url', $seoSettings->facebook_url ?? '') }}">
                    @error('facebook_url')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="twitter_url" class="form-label"><i class="bi bi-twitter"></i> Twitter URL</label>
                    <input type="url" class="form-control @error('twitter_url') is-invalid @enderror" id="twitter_url" name="twitter_url" placeholder="https://twitter.com/yourhandle" value="{{ old('twitter_url', $seoSettings->twitter_url ?? '') }}">
                    @error('twitter_url')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="instagram_url" class="form-label"><i class="bi bi-instagram"></i> Instagram URL</label>
                    <input type="url" class="form-control @error('instagram_url') is-invalid @enderror" id="instagram_url" name="instagram_url" placeholder="https://instagram.com/yourhandle" value="{{ old('instagram_url', $seoSettings->instagram_url ?? '') }}">
                    @error('instagram_url')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="youtube_url" class="form-label"><i class="bi bi-youtube"></i> YouTube URL</label>
                    <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" id="youtube_url" name="youtube_url" placeholder="https://youtube.com/@yourchannel" value="{{ old('youtube_url', $seoSettings->youtube_url ?? '') }}">
                    @error('youtube_url')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="linkedin_url" class="form-label"><i class="bi bi-linkedin"></i> LinkedIn URL</label>
                    <input type="url" class="form-control @error('linkedin_url') is-invalid @enderror" id="linkedin_url" name="linkedin_url" placeholder="https://linkedin.com/company/yourcompany" value="{{ old('linkedin_url', $seoSettings->linkedin_url ?? '') }}">
                    @error('linkedin_url')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="tiktok_url" class="form-label"><i class="bi bi-tiktok"></i> TikTok URL</label>
                    <input type="url" class="form-control @error('tiktok_url') is-invalid @enderror" id="tiktok_url" name="tiktok_url" placeholder="https://tiktok.com/@yourhandle" value="{{ old('tiktok_url', $seoSettings->tiktok_url ?? '') }}">
                    @error('tiktok_url')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Save Social Media Links
                    </button>
                </div>
            </form>
        </div>

        <!-- JSON-LD Schema Tab -->
        <div class="tab-pane fade" id="schemaSettings" role="tabpanel" aria-labelledby="schema-tab">
            <form action="{{ route('admin.settings.update') }}" method="POST" class="row g-3">
                @csrf

                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Enable or disable JSON-LD Schema markup for SEO. These help search engines understand your content better.
                    </div>
                </div>

                <!-- Schema Enable/Disable Checkboxes -->
                <div class="col-12">
                    <h6 class="mb-3">🔍 Enable Schema Types:</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="enable_news_article_schema" name="enable_news_article_schema" value="1" {{ old('enable_news_article_schema', $schemaSettings->enable_news_article_schema ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_news_article_schema">
                                    <strong>NewsArticle Schema</strong>
                                    <br><small class="text-muted">For individual news posts (Google News & Top Stories)</small>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="enable_organization_schema" name="enable_organization_schema" value="1" {{ old('enable_organization_schema', $schemaSettings->enable_organization_schema ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_organization_schema">
                                    <strong>Organization Schema</strong>
                                    <br><small class="text-muted">For publisher information</small>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="enable_website_schema" name="enable_website_schema" value="1" {{ old('enable_website_schema', $schemaSettings->enable_website_schema ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_website_schema">
                                    <strong>WebSite Schema</strong>
                                    <br><small class="text-muted">For search box integration</small>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="enable_breadcrumb_schema" name="enable_breadcrumb_schema" value="1" {{ old('enable_breadcrumb_schema', $schemaSettings->enable_breadcrumb_schema ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_breadcrumb_schema">
                                    <strong>BreadcrumbList Schema</strong>
                                    <br><small class="text-muted">For navigation breadcrumbs</small>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="enable_person_schema" name="enable_person_schema" value="1" {{ old('enable_person_schema', $schemaSettings->enable_person_schema ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_person_schema">
                                    <strong>Person Schema</strong>
                                    <br><small class="text-muted">For author information & credibility</small>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="enable_image_object_schema" name="enable_image_object_schema" value="1" {{ old('enable_image_object_schema', $schemaSettings->enable_image_object_schema ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_image_object_schema">
                                    <strong>ImageObject Schema</strong>
                                    <br><small class="text-muted">For featured images (Google Discover)</small>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="enable_video_object_schema" name="enable_video_object_schema" value="1" {{ old('enable_video_object_schema', $schemaSettings->enable_video_object_schema ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_video_object_schema">
                                    <strong>VideoObject Schema</strong>
                                    <br><small class="text-muted">For video content</small>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="enable_live_blog_schema" name="enable_live_blog_schema" value="1" {{ old('enable_live_blog_schema', $schemaSettings->enable_live_blog_schema ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_live_blog_schema">
                                    <strong>LiveBlogPosting Schema</strong>
                                    <br><small class="text-muted">For breaking news & live updates</small>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="enable_faq_schema" name="enable_faq_schema" value="1" {{ old('enable_faq_schema', $schemaSettings->enable_faq_schema ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_faq_schema">
                                    <strong>FAQPage Schema</strong>
                                    <br><small class="text-muted">For FAQ & analysis articles</small>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="enable_job_posting_schema" name="enable_job_posting_schema" value="1" {{ old('enable_job_posting_schema', $schemaSettings->enable_job_posting_schema ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_job_posting_schema">
                                    <strong>JobPosting Schema</strong>
                                    <br><small class="text-muted">For job listings</small>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="enable_event_schema" name="enable_event_schema" value="1" {{ old('enable_event_schema', $schemaSettings->enable_event_schema ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_event_schema">
                                    <strong>Event Schema</strong>
                                    <br><small class="text-muted">For event coverage</small>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="enable_claim_review_schema" name="enable_claim_review_schema" value="1" {{ old('enable_claim_review_schema', $schemaSettings->enable_claim_review_schema ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="enable_claim_review_schema">
                                    <strong>ClaimReview Schema</strong>
                                    <br><small class="text-muted">For fact-checking articles</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Organization Contact Information -->
                <div class="col-12 mt-4">
                    <hr>
                    <h6 class="mb-3">📋 Organization Information (for Schema):</h6>
                </div>

                <div class="col-md-6">
                    <label for="organization_name" class="form-label">Organization Name</label>
                    <input type="text" class="form-control" id="organization_name" name="organization_name" value="{{ old('organization_name', $schemaSettings->organization_name ?? '') }}">
                    <small class="text-muted">Leave empty to use Site Name</small>
                </div>

                <div class="col-md-6">
                    <label for="contact_email" class="form-label">Contact Email</label>
                    <input type="email" class="form-control" id="contact_email" name="contact_email" value="{{ old('contact_email', $schemaSettings->contact_email ?? '') }}">
                </div>

                <div class="col-md-6">
                    <label for="contact_phone" class="form-label">Contact Phone</label>
                    <input type="tel" class="form-control" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $schemaSettings->contact_phone ?? '') }}">
                </div>

                <div class="col-md-6">
                    <label for="contact_type" class="form-label">Contact Type</label>
                    <input type="text" class="form-control" id="contact_type" name="contact_type" value="{{ old('contact_type', $schemaSettings->contact_type ?? 'Customer Service') }}">
                    <small class="text-muted">e.g., Customer Service, News Inquiry, etc.</small>
                </div>

                <div class="col-12">
                    <label for="organization_description" class="form-label">Organization Description</label>
                    <textarea class="form-control" id="organization_description" name="organization_description" rows="3" maxlength="500">{{ old('organization_description', $schemaSettings->organization_description ?? '') }}</textarea>
                    <small class="text-muted">Max 500 characters</small>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Save Schema Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Push Notifications Settings Tab -->
        <div class="tab-pane fade" id="pushSettings" role="tabpanel" aria-labelledby="push-tab">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf

                <!-- Enable Push Notifications -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary bg-opacity-10">
                            <h5 class="mb-0"><i class="bi bi-bell"></i> Push Notifications Configuration</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="push_notifications_enabled" 
                                       name="push_notifications_enabled" value="1"
                                       {{ old('push_notifications_enabled', optional($seoSettings)->push_notifications_enabled ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="push_notifications_enabled">
                                    <strong>Enable Push Notifications</strong>
                                    <small class="d-block text-muted">Allow users to receive browser notifications about new posts</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- VAPID Public Key -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-success bg-opacity-10">
                            <h6 class="mb-0"><i class="bi bi-key"></i> VAPID Public Key</h6>
                        </div>
                        <div class="card-body">
                            <label for="vapid_public_key" class="form-label">Public Key</label>
                            <textarea class="form-control @error('vapid_public_key') is-invalid @enderror" 
                                      id="vapid_public_key" name="vapid_public_key" rows="3" 
                                      placeholder="Paste your VAPID public key here">{{ old('vapid_public_key', optional($seoSettings)->vapid_public_key ?? '') }}</textarea>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle"></i> Generate keys using: <code>php artisan vapid:generate</code>
                            </small>
                            @error('vapid_public_key')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- VAPID Private Key -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-warning bg-opacity-10">
                            <h6 class="mb-0"><i class="bi bi-key"></i> VAPID Private Key</h6>
                        </div>
                        <div class="card-body">
                            <label for="vapid_private_key" class="form-label">Private Key</label>
                            <textarea class="form-control @error('vapid_private_key') is-invalid @enderror" 
                                      id="vapid_private_key" name="vapid_private_key" rows="3" 
                                      placeholder="Paste your VAPID private key here">{{ old('vapid_private_key', optional($seoSettings)->vapid_private_key ?? '') }}</textarea>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-warning"></i> <strong>Keep this private!</strong> Never share this key.
                            </small>
                            @error('vapid_private_key')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Information Box -->
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        <strong><i class="bi bi-info-circle"></i> Web Push Notifications Guide:</strong>
                        <ul class="mb-0 mt-2 ps-3">
                            <li>VAPID keys are required for sending push notifications to users</li>
                            <li>Generate keys once using: <code>php artisan vapid:generate</code></li>
                            <li>Users opt-in to receive notifications (browser permission required)</li>
                            <li>Only active subscriptions receive notifications</li>
                            <li>Send notifications using: <code>php artisan notifications:send-push {news_id}</code></li>
                            <li>Complies with Google Push Notification Policies</li>
                        </ul>
                    </div>
                </div>

                <!-- Subscriptions Stats -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-secondary bg-opacity-10">
                            <h6 class="mb-0"><i class="bi bi-graph-up"></i> Subscriber Statistics</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h3 class="text-primary">{{ App\Models\PushSubscription::count() }}</h3>
                                        <small class="text-muted">Total Subscriptions</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h3 class="text-success">{{ App\Models\PushSubscription::where('is_active', true)->count() }}</h3>
                                        <small class="text-muted">Active Subscriptions</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h3 class="text-danger">{{ App\Models\PushSubscription::where('is_active', false)->count() }}</h3>
                                        <small class="text-muted">Inactive Subscriptions</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Save Push Notification Settings
                    </button>
                </div>
            </form>
        </div>

<style>
    .alert-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    .preview-container img {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }

    .tab-pane {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
</style>
@endsection
