@extends('layouts.admin')

@section('page-title', 'Advertisements Management')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="bi bi-image"></i> Advertisement Management
            </h1>
            <a href="{{ route('admin.advertisements.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create New Ad
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4 g-3">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                    <div class="stat-card-icon" style="color: white; margin-bottom: 10px;">
                        <i class="bi bi-image" style="font-size: 24px;"></i>
                    </div>
                    <div class="stat-card-value" style="color: white;">{{ $totalAds }}</div>
                    <div class="stat-card-label" style="color: rgba(255,255,255,0.8);">Total Ads</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 15px rgba(245, 87, 108, 0.3);">
                    <div class="stat-card-icon" style="color: white; margin-bottom: 10px;">
                        <i class="bi bi-play-circle" style="font-size: 24px;"></i>
                    </div>
                    <div class="stat-card-value" style="color: white;">{{ $activeAds }}</div>
                    <div class="stat-card-label" style="color: rgba(255,255,255,0.8);">Active Ads</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);">
                    <div class="stat-card-icon" style="color: white; margin-bottom: 10px;">
                        <i class="bi bi-eye" style="font-size: 24px;"></i>
                    </div>
                    <div class="stat-card-value" style="color: white;">{{ number_format($totalImpressions) }}</div>
                    <div class="stat-card-label" style="color: rgba(255,255,255,0.8);">Total Views</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 15px rgba(67, 233, 123, 0.3);">
                    <div class="stat-card-icon" style="color: white; margin-bottom: 10px;">
                        <i class="bi bi-cursor-click" style="font-size: 24px;"></i>
                    </div>
                    <div class="stat-card-value" style="color: white;">{{ number_format($totalClicks) }}</div>
                    <div class="stat-card-label" style="color: rgba(255,255,255,0.8);">Total Clicks</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alerts -->
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Validation Error!</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Advertisements Table -->
<div class="table-wrapper">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Placement</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Clicks</th>
                    <th>CTR</th>
                    <th>Period</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ads as $ad)
                    <tr>
                        <td>
                            <strong>{{ $ad->name }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $ad->placement)) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($ad->type) }}</span>
                        </td>
                        <td>
                            @if ($ad->is_active)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Active
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle"></i> Inactive
                                </span>
                            @endif
                        </td>
                        <td>{{ number_format($ad->views) }}</td>
                        <td>{{ number_format($ad->clicks) }}</td>
                        <td>
                            @if ($ad->views > 0)
                                <span class="text-success fw-bold">{{ $ad->ctr }}%</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ $ad->start_date->format('M d') }}
                                @if ($ad->end_date)
                                    — {{ $ad->end_date->format('M d') }}
                                @else
                                    — ∞
                                @endif
                            </small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.advertisements.show', $ad) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.advertisements.edit', $ad) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.advertisements.destroy', $ad) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 32px; color: #ccc;"></i>
                            <p class="text-muted mt-2">No advertisements found. <a href="{{ route('admin.advertisements.create') }}">Create one now</a></p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
            Showing {{ $ads->firstItem() ?? 0 }} to {{ $ads->lastItem() ?? 0 }} of {{ $ads->total() }} results
        </div>
        {{ $ads->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    .table-wrapper {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .stat-card {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .btn-group {
        gap: 5px;
    }

    .badge {
        padding: 6px 10px;
        font-weight: 500;
    }
</style>
@endsection
