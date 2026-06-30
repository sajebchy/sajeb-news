@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<style>
    .stat-card {
        transition: all 0.3s ease;
    }
    
    a:hover .stat-card {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }
</style>

<div class="row mb-4 g-3">
    <div class="col-12 col-sm-6 col-lg-3">
        <a href="{{ route('admin.news.index') }}" style="text-decoration: none;">
            <div class="stat-card primary" style="cursor: pointer; transition: all 0.3s ease;">
                <div class="stat-card-icon text-primary">
                    <i class="bi bi-file-text"></i>
                </div>
                <div>
                    <div class="stat-card-value">{{ $totalNews ?? 0 }}</div>
                    <div class="stat-card-label">Total News Posts</div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <a href="{{ route('admin.analytics') }}" style="text-decoration: none;">
            <div class="stat-card success" style="cursor: pointer; transition: all 0.3s ease;">
                <div class="stat-card-icon text-success">
                    <i class="bi bi-eye"></i>
                </div>
                <div>
                    <div class="stat-card-value">{{ number_format($totalViews ?? 0) }}</div>
                    <div class="stat-card-label">Total Views</div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <a href="{{ route('admin.users.index') }}" style="text-decoration: none;">
            <div class="stat-card info" style="cursor: pointer; transition: all 0.3s ease;">
                <div class="stat-card-icon text-info">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="stat-card-value">{{ $totalUsers ?? 0 }}</div>
                    <div class="stat-card-label">Total Users</div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <a href="{{ route('admin.newsletters.index') }}" style="text-decoration: none;">
            <div class="stat-card warning" style="cursor: pointer; transition: all 0.3s ease;">
                <div class="stat-card-icon text-warning">
                    <i class="bi bi-envelope"></i>
                </div>
                <div>
                    <div class="stat-card-value">{{ $newsletterSubscribers ?? 0 }}</div>
                    <div class="stat-card-label">Newsletter Subscribers</div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <a href="{{ route('admin.file-manager.index') }}" style="text-decoration: none;">
            <div class="stat-card" style="cursor: pointer; transition: all 0.3s ease; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 8px;">
                <div class="stat-card-icon" style="color: white;">
                    <i class="bi bi-folder2-open"></i>
                </div>
                <div>
                    <div class="stat-card-value" style="color: white;">📁</div>
                    <div class="stat-card-label" style="color: white;">File Manager</div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4 g-3">
    <div class="col-12 col-lg-6">
        <div class="table-wrapper">
            <h5 class="mb-4"><i class="bi bi-graph-up"></i> Views This Month</h5>
            <canvas id="viewsChart"></canvas>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="table-wrapper">
            <h5 class="mb-4"><i class="bi bi-newspaper"></i> News by Category</h5>
            <canvas id="categoriesChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Posts -->
<div class="row mb-4 g-3">
    <div class="col-12">
        <div class="table-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5><i class="bi bi-list-check"></i> Recent News Posts</h5>
                <a href="{{ route('admin.news.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus"></i> Add News
                </a>
            </div>

            @if ($recentNews && $recentNews->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Views</th>
                                <th>Author</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentNews as $news)
                                <tr>
                                    <td>
                                        <strong>{{ substr($news->title, 0, 50) }}{{ strlen($news->title) > 50 ? '...' : '' }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $news->category->name ?? 'Uncategorized' }}</span>
                                    </td>
                                    <td>
                                        @if ($news->status === 'published')
                                            <span class="badge bg-success">Published</span>
                                        @elseif ($news->status === 'draft')
                                            <span class="badge bg-secondary">Draft</span>
                                        @else
                                            <span class="badge bg-warning">Scheduled</span>
                                        @endif
                                    </td>
                                    <td>{{ $news->views ?? 0 }}</td>
                                    <td>{{ $news->author->name ?? 'Unknown' }}</td>
                                    <td>{{ $news->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteNews({{ $news->id }})" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted">No news posts yet. <a href="{{ route('admin.news.create') }}">Create one now</a></p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Live Streams (Admin Only) -->
@if (auth()->user()->hasRole('admin'))
<div class="row mb-4 g-3">
    <div class="col-12">
        <div class="table-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5><i class="bi bi-camera-video"></i> Live Streams</h5>
                <a href="{{ route('admin.live-streams.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus"></i> Start Live Stream
                </a>
            </div>

            @if ($liveStreams && $liveStreams->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Viewers</th>
                                <th>Duration</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($liveStreams as $stream)
                                <tr>
                                    <td>
                                        <strong>{{ substr($stream->title, 0, 40) }}{{ strlen($stream->title) > 40 ? '...' : '' }}</strong>
                                    </td>
                                    <td>
                                        @if ($stream->status === 'live')
                                            <span class="badge bg-danger"><span class="badge bg-danger-pulse"></span> LIVE</span>
                                        @elseif ($stream->status === 'pending')
                                            <span class="badge bg-warning">SCHEDULED</span>
                                        @elseif ($stream->status === 'ended')
                                            <span class="badge bg-secondary">ENDED</span>
                                        @else
                                            <span class="badge bg-info">DRAFT</span>
                                        @endif
                                    </td>
                                    <td>{{ $stream->viewer_count }}</td>
                                    <td>{{ $stream->getFormattedDuration() }}</td>
                                    <td>{{ $stream->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.live-streams.show', $stream) }}" class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.live-streams.edit', $stream) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted">No live streams yet. <a href="{{ route('admin.live-streams.create') }}">Create your first stream</a></p>
                </div>
            @endif
        </div>
    </div>
</div>
@endif

<!-- Activity Log -->
<div class="row g-3">
    <div class="col-12">
        <div class="table-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5><i class="bi bi-clock-history"></i> Recent Activities</h5>
                <a href="{{ route('admin.activities') }}" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>

            @if ($recentActivities && $recentActivities->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentActivities as $activity)
                                <tr>
                                    <td>{{ $activity->user->name ?? 'System' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($activity->action_type) }}</span>
                                    </td>
                                    <td>{{ substr($activity->description, 0, 60) }}{{ strlen($activity->description) > 60 ? '...' : '' }}</td>
                                    <td>{{ $activity->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted">No activities yet</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Views Chart
    const viewsCtx = document.getElementById('viewsChart').getContext('2d');
    new Chart(viewsCtx, {
        type: 'line',
        data: {
            labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
            datasets: [{
                label: 'Views',
                data: [120, 190, 150, 170, 200, 220, 180],
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Categories Chart
    const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
    new Chart(categoriesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Politics', 'Sports', 'Technology', 'Entertainment', 'Others'],
            datasets: [{
                data: [30, 25, 20, 15, 10],
                backgroundColor: ['#0d6efd', '#6c757d', '#198754', '#ffc107', '#0dcaf0']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Delete news function
    function deleteNews(newsId) {
        if (confirm('Are you sure you want to delete this news post?')) {
            // TODO: Implement delete functionality
            console.log('Delete news:', newsId);
        }
    }
</script>
@endpush
@endsection
