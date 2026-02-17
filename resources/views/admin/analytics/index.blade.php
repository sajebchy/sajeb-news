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

<!-- Recent Activities -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="bi bi-clock-history"></i> Recent Activities</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentActivities ?? [] as $activity)
                            <tr>
                                <td>{{ $activity->user->name ?? 'System' }}</td>
                                <td><span class="badge bg-info">{{ $activity->action ?? 'Unknown' }}</span></td>
                                <td>{{ $activity->description ?? '-' }}</td>
                                <td><small class="text-muted">{{ $activity->created_at?->diffForHumans() ?? '-' }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <p class="text-muted mb-0"><i class="bi bi-inbox"></i> No activities found</p>
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
