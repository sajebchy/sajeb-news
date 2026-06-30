@extends('layouts.admin')

@section('page-title', 'Advertisement Details')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3">
                <i class="bi bi-image"></i> {{ $ad->name }}
            </h1>
            <div>
                <a href="{{ route('admin.advertisements.edit', $ad) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('admin.advertisements.destroy', $ad) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
                <a href="{{ route('admin.advertisements.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Main Content -->
    <div class="col-12 col-lg-8">
        <!-- Advertisement Preview -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-image"></i> Advertisement Preview</h5>
            </div>
            <div class="card-body">
                <!-- Image Preview Section -->
                @if (isset($ad) && $ad->image_url && trim($ad->image_url) !== '')
                    <div class="text-center" style="background: linear-gradient(135deg, #f5f7fa 0%, #f8f9fa 100%); padding: 30px 20px; border-radius: 8px; min-height: 280px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                        @php
                            $linkUrl = $ad->full_url ?: $ad->ad_url;
                        @endphp
                        
                        @if ($linkUrl)
                            <a href="{{ $linkUrl }}" target="_blank" rel="noopener noreferrer" class="d-inline-block" style="max-width: 100%; width: 100%; max-width: 700px;">
                        @endif
                                <img src="{{ asset($ad->image_url) }}" 
                                     alt="Advertisement: {{ $ad->name }}"
                                     title="{{ $ad->name }} - Click to visit"
                                     class="img-fluid"
                                     style="max-width: 100%; height: auto; max-height: 550px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border: 1px solid #e9ecef; display: block; margin: 0 auto;">
                        @if ($linkUrl)
                            </a>
                        @endif
                        
                        <p class="text-muted small mt-4 mb-0" style="font-size: 13px;">
                            <i class="bi bi-link-45deg"></i> 
                            @if ($linkUrl)
                                Destination URL: <code style="background: #e9ecef; padding: 2px 6px; border-radius: 3px; font-size: 11px;">{{ parse_url($linkUrl, PHP_URL_HOST) }}</code>
                            @else
                                (No destination URL set)
                            @endif
                        </p>
                    </div>
                @else
                    <div class="text-center py-5" style="min-height: 300px; background: linear-gradient(135deg, #f5f7fa 0%, #f8f9fa 100%); border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <i class="bi bi-image" style="font-size: 100px; color: #dee2e6; margin-bottom: 20px;"></i>
                        <p class="text-muted mb-0" style="font-size: 16px;">
                            @if (!isset($ad))
                                Advertisement not found
                            @else
                                No image provided for this advertisement
                            @endif
                        </p>
                        @if (isset($ad) && $ad->id)
                            <small class="text-secondary mt-2">Ad ID: {{ $ad->id }}</small>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Basic Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Basic Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted">Name</small>
                        <p class="fw-bold">{{ $ad->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Slug</small>
                        <p class="fw-bold">{{ $ad->slug }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted">Placement</small>
                        <p><span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $ad->placement)) }}</span></p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Type</small>
                        <p><span class="badge bg-secondary">{{ ucfirst($ad->type) }}</span></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted">Status</small>
                        <p>
                            @if ($ad->is_active)
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Active</span>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Inactive</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Device Target</small>
                        <p><span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $ad->device_target)) }}</span></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted">Display Order</small>
                        <p class="fw-bold">{{ $ad->display_order }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Device Preferences</small>
                        <p>
                            @if ($ad->show_on_mobile)
                                <span class="badge bg-success"><i class="bi bi-phone"></i> Mobile</span>
                            @endif
                            @if ($ad->show_on_desktop)
                                <span class="badge bg-success"><i class="bi bi-display"></i> Desktop</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- URLs -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-link"></i> Links & URLs</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Image URL</small>
                    <p class="text-break small">
                        <code>{{ $ad->image_url }}</code>
                    </p>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Destination URL</small>
                    <p class="text-break small">
                        <code>{{ $ad->ad_url }}</code>
                    </p>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Full URL with UTM Parameters</small>
                    <p class="text-break small" style="background: #f8f9fa; padding: 10px; border-radius: 5px;">
                        <code>{{ $ad->full_url }}</code>
                    </p>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Alt Text</small>
                    <p>{{ $ad->alt_text ?? 'Not provided' }}</p>
                </div>
            </div>
        </div>

        <!-- UTM Parameters -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> UTM Parameters</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td><strong>UTM Source</strong></td>
                        <td>{{ $ad->utm_source ?? 'Not set' }}</td>
                    </tr>
                    <tr>
                        <td><strong>UTM Medium</strong></td>
                        <td>{{ $ad->utm_medium ?? 'Not set' }}</td>
                    </tr>
                    <tr>
                        <td><strong>UTM Campaign</strong></td>
                        <td>{{ $ad->utm_campaign ?? 'Not set' }}</td>
                    </tr>
                    <tr>
                        <td><strong>UTM Term</strong></td>
                        <td>{{ $ad->utm_term ?? 'Not set' }}</td>
                    </tr>
                    <tr>
                        <td><strong>UTM Content</strong></td>
                        <td>{{ $ad->utm_content ?? 'Not set' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Schedule -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Schedule</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted">Start Date</small>
                        <p class="fw-bold">{{ $ad->start_date->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">End Date</small>
                        <p class="fw-bold">
                            @if ($ad->end_date)
                                {{ $ad->end_date->format('M d, Y H:i') }}
                            @else
                                <span class="text-muted">No end date (Indefinite)</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-12 col-lg-4">
        <!-- Performance Stats -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Performance</h5>
            </div>
            <div class="card-body">
                <div class="mb-3 text-center">
                    <div style="font-size: 28px; color: #667eea; font-weight: bold;">{{ number_format($ad->views) }}</div>
                    <small class="text-muted">Total Views</small>
                </div>

                <div class="mb-3 text-center">
                    <div style="font-size: 28px; color: #f5576c; font-weight: bold;">{{ number_format($ad->clicks) }}</div>
                    <small class="text-muted">Total Clicks</small>
                </div>

                <div class="text-center">
                    <div style="font-size: 28px; color: #43e97b; font-weight: bold;">{{ $ad->ctr }}%</div>
                    <small class="text-muted">Click-Through Rate (CTR)</small>
                </div>
            </div>
        </div>

        <!-- Advertiser Info -->
        @if ($ad->advertiser_name)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-badge"></i> Advertiser</h5>
                </div>
                <div class="card-body small">
                    @if ($ad->advertiser_name)
                        <p><strong>Name:</strong> {{ $ad->advertiser_name }}</p>
                    @endif
                    @if ($ad->advertiser_email)
                        <p><strong>Email:</strong> <a href="mailto:{{ $ad->advertiser_email }}">{{ $ad->advertiser_email }}</a></p>
                    @endif
                    @if ($ad->advertiser_phone)
                        <p><strong>Phone:</strong> <a href="tel:{{ $ad->advertiser_phone }}">{{ $ad->advertiser_phone }}</a></p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Pricing Info -->
        @if ($ad->cpc_amount || $ad->cpm_amount)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-wallet2"></i> Pricing</h5>
                </div>
                <div class="card-body small">
                    @if ($ad->cpc_amount)
                        <p><strong>CPC (Cost Per Click):</strong> ৳{{ number_format($ad->cpc_amount, 2) }}</p>
                    @endif
                    @if ($ad->cpm_amount)
                        <p><strong>CPM (Per 1000 Impressions):</strong> ৳{{ number_format($ad->cpm_amount, 2) }}</p>
                    @endif
                    @if ($ad->total_spent)
                        <p><strong>Total Spent:</strong> ৳{{ number_format($ad->total_spent, 2) }}</p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Limits -->
        @if ($ad->daily_impression_limit || $ad->max_clicks_per_day)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Limits</h5>
                </div>
                <div class="card-body small">
                    @if ($ad->daily_impression_limit)
                        <p><strong>Daily Impression Limit:</strong> {{ number_format($ad->daily_impression_limit) }}</p>
                    @endif
                    @if ($ad->max_clicks_per_day)
                        <p><strong>Max Clicks Per Day:</strong> {{ number_format($ad->max_clicks_per_day) }}</p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Meta Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Meta Information</h5>
            </div>
            <div class="card-body small">
                <p><strong>Created:</strong> {{ $ad->created_at->format('M d, Y H:i') }}</p>
                <p><strong>Updated:</strong> {{ $ad->updated_at->format('M d, Y H:i') }}</p>
                <p><strong>Created By:</strong> {{ $ad->creator->name ?? 'Unknown' }}</p>
                @if ($ad->notes)
                    <p><strong>Notes:</strong></p>
                    <p class="text-muted">{{ $ad->notes }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px 8px 0 0 !important;
    border: none;
    padding: 15px;
}

.card-header h5 {
    color: white;
    margin-bottom: 0;
}

.badge {
    padding: 6px 10px;
    font-weight: 500;
}

.text-break {
    word-break: break-word;
}

code {
    background: #f8f9fa;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 12px;
}
</style>
@endsection
