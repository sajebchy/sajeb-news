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

<!-- Visitors Table -->
<div class="card shadow-sm">
    <div class="table-wrapper">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light sticky-top">
                    <tr>
                        <th style="width: 15%;">Visitor Info</th>
                        <th style="width: 12%;">Location</th>
                        <th style="width: 15%;">Device</th>
                        <th style="width: 15%;">Referrer Source</th>
                        <th style="width: 12%;">Reading Time</th>
                        <th style="width: 15%;">Visit Details</th>
                        <th style="width: 16%;">Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($visitors as $visitor)
                        <tr>
                            <td>
                                <div>
                                    <strong><i class="bi bi-globe"></i> {{ $visitor->visitor_ip ?? 'Unknown' }}</strong>
                                    @if($visitor->browser)
                                        <br><small class="text-muted">{{ $visitor->browser }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    @if($visitor->visitor_country)
                                        <strong>{{ $visitor->visitor_country }}</strong>
                                    @else
                                        <span class="text-muted">Unknown</span>
                                    @endif
                                    @if($visitor->visitor_city)
                                        <br><small class="text-muted">{{ $visitor->visitor_city }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    @if($visitor->visitor_device)
                                        @if(str_contains($visitor->visitor_device, 'Mobile'))
                                            <span class="badge bg-info"><i class="bi bi-phone"></i> Mobile</span>
                                        @elseif(str_contains($visitor->visitor_device, 'Tablet'))
                                            <span class="badge bg-info"><i class="bi bi-tablet"></i> Tablet</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="bi bi-laptop"></i> Desktop</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Unknown</span>
                                    @endif
                                    @if($visitor->os)
                                        <br><small class="text-muted">{{ $visitor->os }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
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
                                    <span class="badge bg-{{ $sourceColors[$visitor->referrer_source] ?? 'secondary' }}">
                                        <i class="bi {{ $visitor->sourceIcon }}"></i>
                                        {{ ucfirst($visitor->referrer_source ?? 'Direct') }}
                                    </span>
                                    @if($visitor->referrer_url)
                                        <br><small class="text-muted" style="word-break: break-word;">{{ substr($visitor->referrer_url, 0, 40) }}...</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $visitor->readingTime }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $visitor->scroll_percentage }}% scroll</small>
                                    @if($visitor->completed_reading)
                                        <br><span class="badge bg-success"><i class="bi bi-check-circle"></i> Completed</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 12px;">
                                    <small class="text-muted">
                                        <i class="bi bi-eye"></i> Views: <strong>1</strong><br>
                                        <i class="bi bi-clock"></i> Duration: <strong>{{ $visitor->time_spent_seconds }}s</strong><br>
                                        <i class="bi bi-arrow-up"></i> Scroll: <strong>{{ $visitor->scroll_percentage }}%</strong>
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 12px;">
                                    <small><i class="bi bi-calendar-event"></i> {{ $visitor->visit_date?->format('M d, Y') ?? '-' }}</small><br>
                                    <small><i class="bi bi-clock"></i> {{ $visitor->visit_date?->format('H:i:s') ?? '-' }}</small><br>
                                    <small class="text-primary">{{ $visitor->visit_date?->diffForHumans() ?? '-' }}</small>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 48px; color: #ddd;"></i>
                                <p class="text-muted mt-3">No visitor data found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($visitors instanceof \Illuminate\Pagination\Paginator && $visitors->hasPages())
            <div class="card-footer">
                {{ $visitors->links() }}
            </div>
        @endif
    </div>
</div>

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
        .table th {
            font-size: 12px;
        }

        .table td {
            font-size: 12px;
            padding: 8px !important;
        }
    }
</style>

@endsection
