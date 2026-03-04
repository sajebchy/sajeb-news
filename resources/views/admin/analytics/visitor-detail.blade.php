@extends('layouts.admin')

@section('page-title', 'Visitor Details - ' . $news->title)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.analytics.show', $news->id) }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Back to Analytics
    </a>
    <h5 class="mt-3"><i class="bi bi-person-badge"></i> Detailed Visitor Information</h5>
    <small class="text-muted">Article: {{ $news->title }}</small>
</div>

<!-- Main Visitor Information Card -->
<div class="row mb-4">
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-info-circle"></i> Visitor Profile</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">IP Address</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $visitor->visitor_ip ?? 'Unknown' }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('{{ $visitor->visitor_ip }}')">
                            <i class="bi bi-copy"></i>
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Country</label>
                        <input type="text" class="form-control" value="{{ $visitor->visitor_country ?? 'Unknown' }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">City</label>
                        <input type="text" class="form-control" value="{{ $visitor->visitor_city ?? 'Unknown' }}" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Device Type</label>
                        <input type="text" class="form-control" value="{{ $visitor->visitor_device ?? 'Unknown' }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Operating System</label>
                        <input type="text" class="form-control" value="{{ $visitor->os ?? 'Unknown' }}" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Browser</label>
                    <input type="text" class="form-control" value="{{ $visitor->browser ?? 'Unknown' }}" readonly>
                </div>

                @if($visitor->screen_resolution)
                    <div class="mb-3">
                        <label class="form-label text-muted">Screen Resolution</label>
                        <input type="text" class="form-control" value="{{ $visitor->screen_resolution }}" readonly>
                    </div>
                @endif

                @if($visitor->language)
                    <div class="mb-3">
                        <label class="form-label text-muted">Language</label>
                        <input type="text" class="form-control" value="{{ $visitor->language }}" readonly>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label text-muted">User Agent</label>
                    <textarea class="form-control" readonly rows="3">{{ $visitor->user_agent ?? 'N/A' }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-clock-history"></i> Visit Information</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Visit Date</label>
                        <input type="text" class="form-control" value="{{ $visitor->visit_date?->format('M d, Y') ?? '-' }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Visit Time</label>
                        <input type="text" class="form-control" value="{{ $visitor->visit_date?->format('H:i:s') ?? '-' }}" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Time Spent on Article</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $visitor->readingTime }}" readonly>
                        <span class="input-group-text">{{ $visitor->time_spent_seconds }} seconds</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Scroll Depth</label>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar {{ $visitor->scroll_percentage >= 75 ? 'bg-success' : ($visitor->scroll_percentage >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                             role="progressbar" 
                             style="width: {{ $visitor->scroll_percentage }}%;">
                            {{ $visitor->scroll_percentage }}%
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Completed Reading</label>
                        <div class="input-group">
                            <span class="input-group-text w-100">
                                @if($visitor->completed_reading)
                                    <span class="badge bg-success w-100"><i class="bi bi-check-circle"></i> Yes</span>
                                @else
                                    <span class="badge bg-danger w-100"><i class="bi bi-x-circle"></i> No</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Referrer Source</label>
                        <input type="text" class="form-control" value="{{ ucfirst($visitor->referrer_source ?? 'Direct') }}" readonly>
                    </div>
                </div>

                @if($visitor->referrer_url)
                    <div class="mb-3">
                        <label class="form-label text-muted">Referrer URL</label>
                        <textarea class="form-control" readonly rows="2">{{ $visitor->referrer_url }}</textarea>
                    </div>
                @endif

                @if($visitor->exit_time)
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Exit Time</label>
                            <input type="text" class="form-control" value="{{ $visitor->exit_time?->format('H:i:s') }}" readonly>
                        </div>
                        @if($visitor->exit_page)
                            <div class="col-md-6">
                                <label class="form-label text-muted">Exit Page</label>
                                <input type="text" class="form-control" value="{{ $visitor->exit_page }}" readonly>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Next Destination After Reading -->
@if($visitor->nextNews)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-arrow-right-circle"></i> Last Destination After Reading</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>{{ $visitor->nextNews->title }}</h5>
                            <p class="text-muted">
                                <i class="bi bi-bookmark"></i> {{ $visitor->nextNews->category?->name ?? 'Uncategorized' }}
                                <span class="mx-2">•</span>
                                <i class="bi bi-calendar-event"></i> {{ $visitor->nextNews->published_at?->format('M d, Y') }}
                            </p>
                            <p>{{ Str::limit($visitor->nextNews->excerpt ?? $visitor->nextNews->content, 200) }}</p>
                            <a href="{{ route('admin.news.edit', $visitor->nextNews->id) }}" class="btn btn-sm btn-success">
                                <i class="bi bi-pencil-square"></i> Edit Article
                            </a>
                            <a href="{{ route('news.show', $visitor->nextNews->slug) }}" class="btn btn-sm btn-outline-success" target="_blank">
                                <i class="bi bi-eye"></i> View Article
                            </a>
                        </div>
                        <div class="col-md-4">
                            @if($visitor->nextNews->featured_image)
                                <img src="{{ asset('storage/' . $visitor->nextNews->featured_image) }}" 
                                     alt="{{ $visitor->nextNews->title }}" 
                                     class="img-fluid rounded" 
                                     style="max-height: 200px; object-fit: cover; width: 100%;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="bi bi-image" style="font-size: 48px; color: #ccc;"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> No next article visited after reading this article.
    </div>
@endif

<!-- Visitor Journey Timeline -->
<div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
        <h6 class="mb-0"><i class="bi bi-diagram-3"></i> Visitor Journey (All Articles Visited from This IP)</h6>
    </div>
    <div class="card-body">
        @if($visitorJourney->count() > 0)
            <div class="timeline">
                @foreach($visitorJourney as $visit)
                    <div class="timeline-item">
                        <div class="timeline-marker {{ $visit->id === $visitor->id ? 'active' : '' }}">
                            <i class="bi bi-{{ $visit->id === $visitor->id ? 'star-fill' : 'dot' }}"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">
                                <a href="{{ route('admin.news.edit', $visit->news->id) }}" class="text-decoration-none">
                                    {{ $visit->news->title }}
                                </a>
                                @if($visit->id === $visitor->id)
                                    <span class="badge bg-primary">Current Article</span>
                                @endif
                            </h6>
                            <small class="text-muted d-block">
                                <i class="bi bi-calendar-event"></i> {{ $visit->visit_date?->format('M d, Y H:i:s') }}
                                <span class="mx-2">•</span>
                                <i class="bi bi-clock"></i> {{ $visit->readingTime }}
                                <span class="mx-2">•</span>
                                <i class="bi bi-graph-up"></i> {{ $visit->scroll_percentage }}% scroll
                            </small>
                            <small class="text-muted d-block">
                                <i class="bi bi-arrow-right"></i> Referred from: <strong>{{ ucfirst($visit->referrer_source ?? 'Direct') }}</strong>
                            </small>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted mb-0">No visit history found for this IP address.</p>
        @endif
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding: 20px 0;
    }

    .timeline-item {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        position: relative;
    }

    .timeline-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 12px;
        top: 40px;
        width: 2px;
        height: calc(100% + 10px);
        background: #dee2e6;
    }

    .timeline-marker {
        min-width: 26px;
        height: 26px;
        background: #f8f9fa;
        border: 2px solid #dee2e6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }

    .timeline-marker.active {
        background: #007bff;
        border-color: #0056b3;
        color: white;
        width: 32px;
        height: 32px;
    }

    .timeline-content {
        flex: 1;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 6px;
    }

    .timeline-item:hover .timeline-content {
        background: #e9ecef;
    }
</style>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('IP address copied to clipboard!');
    });
}
</script>

@endsection
