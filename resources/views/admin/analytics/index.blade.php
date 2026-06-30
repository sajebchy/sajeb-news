@extends('layouts.admin')

@section('page-title', 'Analytics')

@section('content')
<div class="mb-4">
    <h5><i class="bi bi-graph-up"></i> Analytics Dashboard</h5>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="stat-card primary">
            <div class="stat-card-icon text-primary">
                <i class="bi bi-eye"></i>
            </div>
            <div class="stat-card-value">{{ number_format($totalViews ?? 0) }}</div>
            <div class="stat-card-label">Total Views</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="stat-card success">
            <div class="stat-card-icon text-success">
                <i class="bi bi-file-text"></i>
            </div>
            <div class="stat-card-value">{{ $totalNews ?? 0 }}</div>
            <div class="stat-card-label">Total Posts</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="stat-card info">
            <div class="stat-card-icon text-info">
                <i class="bi bi-folder"></i>
            </div>
            <div class="stat-card-value">{{ $totalCategories ?? 0 }}</div>
            <div class="stat-card-label">Categories</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="stat-card warning">
            <div class="stat-card-icon text-warning">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-card-value">{{ $totalUsers ?? 0 }}</div>
            <div class="stat-card-label">Total Users</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-6 mb-3">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="bi bi-fire"></i> Top Performing News</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Views</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topNews ?? [] as $news)
                            <tr>
                                <td>{{ \Str::limit($news->title, 35) }}</td>
                                <td>
                                    <span class="badge bg-primary"><i class="bi bi-eye"></i> {{ $news->views ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $news->category->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.analytics.show', $news->id) }}" class="btn btn-xs btn-info" title="View Visitor Details">
                                        <i class="bi bi-people"></i> Visitors
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6 mb-3">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="bi bi-folder"></i> News by Category</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Category</th>
                            <th>Posts</th>
                            <th>Views</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categoryAnalytics ?? [] as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $category->news_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ number_format($category->total_views ?? 0) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Traffic Sources Breakdown -->
<div class="row mt-3 mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-diagram-3"></i> ট্র্যাফিক সোর্স — কোথা থেকে পাঠক আসছে</h6>
                <span class="badge bg-secondary">মোট: {{ number_format($totalVisitors) }} ভিজিট</span>
            </div>
            <div class="card-body">
                @if($sourceBreakdown->count() > 0)
                <div class="row g-3 mb-4">
                    @foreach($sourceBreakdown as $src)
                    @php $pct = round(($src->total / $totalVisitors) * 100, 1); @endphp
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="d-flex align-items-center gap-3 p-3 border rounded-3 bg-white shadow-sm">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:48px;height:48px;background:{{ $src->color }}22;color:{{ $src->color }};font-size:22px;">
                                <i class="bi {{ $src->icon }}"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold text-dark">{{ $src->label }}</div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <div class="progress flex-grow-1" style="height:6px;border-radius:3px;">
                                        <div class="progress-bar" style="width:{{ $pct }}%;background:{{ $src->color }};"></div>
                                    </div>
                                    <small class="text-muted fw-semibold text-nowrap">{{ number_format($src->total) }} ({{ $pct }}%)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                    এখনো কোনো ভিজিটর ডেটা নেই। নিউজ পেজে ভিজিটর আসলে এখানে দেখা যাবে।
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Real Time Visitor Activity -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="bi bi-geo-alt"></i> Real Time Visitor Activity</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>IP Address</th>
                            <th>Location & Device</th>
                            <th>Action</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentVisitors ?? [] as $visitor)
                            <tr>
                                <td>
                                    <code class="text-primary">{{ $visitor->visitor_ip }}</code>
                                </td>
                                <td>
                                    <div>
                                        <small class="text-muted">
                                            <i class="bi bi-geo-alt"></i> {{ $visitor->visitor_city }}, {{ $visitor->visitor_country }}
                                        </small>
                                    </div>
                                    <small class="text-secondary">
                                        <i class="bi bi-phone"></i> {{ $visitor->visitor_device }} - {{ $visitor->browser }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <i class="{{ $visitor->source_icon }}"></i> {{ ucfirst($visitor->referrer_source) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $visitor->visit_date?->diffForHumans() ?? '-' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <p class="text-muted mb-0"><i class="bi bi-inbox"></i> No visitor activity found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    .stat-card-icon {
        font-size: 32px;
        margin-bottom: 10px;
    }

    .stat-card-value {
        font-size: 28px;
        font-weight: bold;
        color: #333;
        margin: 10px 0;
    }

    .stat-card-label {
        font-size: 13px;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
@endpush

@endsection
