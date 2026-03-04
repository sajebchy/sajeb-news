@extends('layouts.admin')

@section('page-title', 'Visitor Analytics - ' . $news->title)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.analytics') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Back to Analytics
    </a>
    <h5 class="mt-3"><i class="bi bi-graph-up"></i> Visitor Analytics for: {{ $news->title }}</h5>
</div>

<!-- Summary Statistics -->
<div class="row mb-4">
    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="stat-card primary">
            <div class="stat-card-icon text-primary">
                <i class="bi bi-eye"></i>
            </div>
            <div class="stat-card-value">{{ $visitors->count() }}</div>
            <div class="stat-card-label">Total Visitors</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="stat-card info">
            <div class="stat-card-icon text-info">
                <i class="bi bi-clock"></i>
            </div>
            <div class="stat-card-value">{{ round($avgReadingTime) }}m</div>
            <div class="stat-card-label">Avg Reading Time</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="stat-card success">
            <div class="stat-card-icon text-success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-card-value">{{ $completedReading }}%</div>
            <div class="stat-card-label">Completed Reading</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="stat-card warning">
            <div class="stat-card-icon text-warning">
                <i class="bi bi-share"></i>
            </div>
            <div class="stat-card-value">{{ $topSource }}</div>
            <div class="stat-card-label">Top Referrer</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by IP or country..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="source" class="form-select">
                    <option value="">All Sources</option>
                    <option value="google" @selected(request('source') === 'google')>Google</option>
                    <option value="facebook" @selected(request('source') === 'facebook')>Facebook</option>
                    <option value="twitter" @selected(request('source') === 'twitter')>Twitter</option>
                    <option value="chatgpt" @selected(request('source') === 'chatgpt')>ChatGPT</option>
                    <option value="direct" @selected(request('source') === 'direct')>Direct</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Visitors List - Card View -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="bi bi-people"></i> Visitor Details</h6>
    </div>
    <div class="card-body p-0">
        @forelse ($visitors as $visitor)
            <div class="visitor-card shadow-sm mb-3 p-4" style="border-left: 4px solid #667eea; background: #f9f9f9;">
                <div class="row">
                    <!-- Visitor Info -->
                    <div class="col-md-3 col-lg-2 mb-3 mb-md-0">
                        <div class="visitor-info">
                            <label class="form-label text-muted small"><i class="bi bi-person-badge"></i> Visitor Info</label>
                            <div>
                                <strong class="d-block"><i class="bi bi-globe"></i> {{ $visitor->visitor_ip ?? 'Unknown' }}</strong>
                                <small class="text-muted d-block">{{ $visitor->browser ?? 'Unknown Browser' }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="col-md-3 col-lg-2 mb-3 mb-md-0">
                        <div class="visitor-location">
                            <label class="form-label text-muted small"><i class="bi bi-geo-alt"></i> Location</label>
                            <div>
                                <strong class="d-block">{{ $visitor->visitor_country ?? 'Unknown' }}</strong>
                                <small class="text-muted d-block">{{ $visitor->visitor_city ?? '-' }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Device -->
                    <div class="col-md-3 col-lg-2 mb-3 mb-md-0">
                        <div class="visitor-device">
                            <label class="form-label text-muted small"><i class="bi bi-phone"></i> Device</label>
                            <div>
                                @if($visitor->visitor_device)
                                    @if(str_contains($visitor->visitor_device, 'Mobile'))
                                        <span class="badge bg-info" style="font-size: 0.85rem;"><i class="bi bi-phone"></i> Mobile</span>
                                    @elseif(str_contains($visitor->visitor_device, 'Tablet'))
                                        <span class="badge bg-info" style="font-size: 0.85rem;"><i class="bi bi-tablet"></i> Tablet</span>
                                    @else
                                        <span class="badge bg-secondary" style="font-size: 0.85rem;"><i class="bi bi-laptop"></i> Desktop</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Unknown</span>
                                @endif
                                <small class="text-muted d-block">{{ $visitor->os ?? '-' }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Referrer Source -->
                    <div class="col-md-3 col-lg-2 mb-3 mb-md-0">
                        <div class="visitor-referrer">
                            <label class="form-label text-muted small"><i class="bi bi-link"></i> Referrer Source</label>
                            <div>
                                @php
                                    $sourceColors = [
                                        'google' => 'primary',
                                        'facebook' => 'info',
                                        'twitter' => 'info',
                                        'linkedin' => 'primary',
                                        'whatsapp' => 'success',
                                        'bing' => 'primary',
                                        'chatgpt' => 'warning',
                                        'direct' => 'secondary',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $sourceColors[$visitor->referrer_source] ?? 'secondary' }}" style="font-size: 0.85rem;">
                                    {{ ucfirst($visitor->referrer_source ?? 'Direct') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Reading Time -->
                    <div class="col-md-3 col-lg-2 mb-3 mb-md-0">
                        <div class="visitor-reading">
                            <label class="form-label text-muted small"><i class="bi bi-clock"></i> Reading Time</label>
                            <div>
                                <strong class="d-block">{{ $visitor->readingTime }}</strong>
                                <small class="text-muted d-block">{{ $visitor->scroll_percentage }}% scroll</small>
                                @if($visitor->completed_reading)
                                    <span class="badge bg-success" style="font-size: 0.75rem;"><i class="bi bi-check-circle"></i> Completed</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Visit Details -->
                    <div class="col-md-3 col-lg-2 mb-3 mb-md-0">
                        <div class="visitor-details">
                            <label class="form-label text-muted small"><i class="bi bi-info-circle"></i> Visit Details</label>
                            <div style="font-size: 0.85rem;">
                                <small class="text-muted d-block"><i class="bi bi-clock"></i> {{ $visitor->time_spent_seconds }}s</small>
                                <small class="text-muted d-block"><i class="bi bi-arrow-up"></i> {{ $visitor->scroll_percentage }}%</small>
                            </div>
                        </div>
                    </div>

                    <!-- Date & Time -->
                    <div class="col-md-3 col-lg-2 mb-3 mb-md-0">
                        <div class="visitor-date">
                            <label class="form-label text-muted small"><i class="bi bi-calendar-event"></i> Date & Time</label>
                            <div style="font-size: 0.85rem;">
                                <small class="text-muted d-block">{{ $visitor->visit_date?->format('M d, Y') ?? '-' }}</small>
                                <small class="text-muted d-block">{{ $visitor->visit_date?->format('H:i:s') ?? '-' }}</small>
                                <small class="text-primary">{{ $visitor->visit_date?->diffForHumans() ?? '-' }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                <!-- Action -->
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="bi bi-fingerprint"></i> ID: {{ $visitor->id }}
                    </small>
                    <a href="{{ route('admin.analytics.visitor-detail', [$news->id, $visitor->id]) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye"></i> View Detailed Info
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 48px; color: #ddd;"></i>
                <p class="text-muted mt-3">No visitor data found.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Pagination -->
@if ($visitors instanceof \Illuminate\Pagination\Paginator && $visitors->hasPages())
    <nav aria-label="Page navigation" class="mt-4">
        {{ $visitors->links() }}
    </nav>
@endif

<style>
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        border-left: 4px solid var(--primary);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .stat-card.primary {
        border-left-color: var(--primary);
    }

    .stat-card.info {
        border-left-color: var(--info);
    }

    .stat-card.success {
        border-left-color: var(--success);
    }

    .stat-card.warning {
        border-left-color: var(--warning);
    }

    .stat-card-icon {
        font-size: 32px;
        margin-bottom: 10px;
    }

    .stat-card-value {
        font-size: 28px;
        font-weight: bold;
        color: #333;
    }

    .stat-card-label {
        color: #999;
        font-size: 14px;
    }

    /* Visitor Card Styles */
    .visitor-card {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    .visitor-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1) !important;
        border-left-color: #0056b3 !important;
    }

    .visitor-card label {
        font-weight: 600;
        margin-bottom: 8px;
    }

    .visitor-info, 
    .visitor-location, 
    .visitor-device, 
    .visitor-referrer, 
    .visitor-reading,
    .visitor-details,
    .visitor-date {
        padding: 10px 0;
    }

    .visitor-card .badge {
        display: inline-block;
    }

    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }

    .table-wrapper {
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }

    @media (max-width: 768px) {
        .visitor-card {
            padding: 20px !important;
        }

        .visitor-info, 
        .visitor-location, 
        .visitor-device, 
        .visitor-referrer, 
        .visitor-reading,
        .visitor-details,
        .visitor-date {
            margin-bottom: 15px !important;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .visitor-date {
            border-bottom: none;
        }

        .table td {
            font-size: 12px;
            padding: 8px !important;
        }
    }
</style>

@endsection
