@extends('layouts.admin')

@section('title', 'Live Streams')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="mb-0">
                <i class="bi bi-camera-video"></i> Live Streams
            </h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.live-streams.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Create New Stream
            </a>
        </div>
    </div>

    @if($streams->count())
        <div class="row">
            @foreach($streams as $stream)
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm hover-shadow">
                        {{-- Preview --}}
                        <div class="position-relative">
                            @php $preview = $stream->getPreviewImage(); @endphp
                            @if($preview)
                                <a href="{{ route('admin.live-streams.show', $stream) }}" class="d-block position-relative">
                                    <img src="{{ $preview }}" class="card-img-top" alt="{{ $stream->title }}" style="height: 200px; object-fit: cover; background:#000;">
                                    {{-- Play overlay --}}
                                    <span class="position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle"
                                          style="width:56px;height:56px;background:rgba(0,0,0,.55);">
                                        <i class="bi bi-play-fill text-white" style="font-size:1.9rem;"></i>
                                    </span>
                                </a>
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center text-white"
                                     style="height: 200px; background:linear-gradient(135deg,#334155,#0f172a);">
                                    <i class="bi {{ $stream->getEmbedPlatform() === 'facebook' ? 'bi-facebook' : 'bi-camera-video' }}" style="font-size:2.5rem;"></i>
                                    <small class="mt-2 opacity-75">প্রিভিউ নেই</small>
                                </div>
                            @endif

                            {{-- Status Badge --}}
                            <span class="position-absolute top-0 start-0 mt-2 ms-2">
                                @if($stream->status === 'live')
                                    <span class="badge bg-danger"><i class="bi bi-circle-fill animate-pulse"></i> LIVE</span>
                                @elseif($stream->status === 'pending')
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> SCHEDULED</span>
                                @elseif($stream->status === 'ended')
                                    <span class="badge bg-secondary"><i class="bi bi-check-lg"></i> ENDED</span>
                                @else
                                    <span class="badge bg-info"><i class="bi bi-file-earmark-text"></i> DRAFT</span>
                                @endif
                            </span>

                            {{-- Featured Badge --}}
                            @if($stream->is_featured)
                                <span class="position-absolute top-0 end-0 mt-2 me-2">
                                    <span class="badge bg-warning"><i class="bi bi-star-fill"></i> Featured</span>
                                </span>
                            @endif
                        </div>

                        <div class="card-body">
                            <h5 class="card-title mb-2">{{ $stream->title }}</h5>
                            
                            @if($stream->description)
                                <p class="card-text text-muted small mb-3">
                                    {{ Str::limit($stream->description, 100) }}
                                </p>
                            @endif

                            {{-- Stream Info --}}
                            <div class="row text-center mb-3 small">
                                <div class="col-4">
                                    <div class="text-muted">Views</div>
                                    <div class="fw-bold">{{ number_format($stream->view_count) }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-muted">Viewers</div>
                                    <div class="fw-bold">{{ number_format($stream->viewer_count) }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-muted">Duration</div>
                                    <div class="fw-bold">{{ $stream->getFormattedDuration() ?: '-' }}</div>
                                </div>
                            </div>

                            {{-- Stream Source Info --}}
                            <div class="alert alert-info py-2 px-3 mb-3 small">
                                <div class="text-muted">Source:</div>
                                <div class="fw-bold text-capitalize">{{ $stream->getEmbedPlatform() ?? 'N/A' }}</div>
                            </div>

                            {{-- Scheduled/Started Info --}}
                            @if($stream->scheduled_at)
                                <div class="mb-2 small text-muted">
                                    <i class="bi bi-calendar3"></i> 
                                    Scheduled: {{ $stream->scheduled_at->format('M d, Y H:i') }}
                                </div>
                            @endif

                            @if($stream->started_at)
                                <div class="mb-3 small text-muted">
                                    <i class="bi bi-play-circle"></i> 
                                    Started: {{ $stream->started_at->format('M d, Y H:i') }}
                                </div>
                            @endif
                        </div>

                        <div class="card-footer bg-transparent">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.live-streams.show', $stream) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View Details
                                </a>
                            </div>
                            <div class="row gap-2 mt-2">
                                <div class="col">
                                    <a href="{{ route('admin.live-streams.edit', $stream) }}" class="btn btn-sm btn-outline-secondary w-100">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                </div>
                                <div class="col">
                                    @if($stream->status === 'draft' || $stream->status === 'pending')
                                        <form action="{{ route('admin.live-streams.start', $stream) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success w-100">
                                                <i class="bi bi-broadcast"></i> Start
                                            </button>
                                        </form>
                                    @elseif($stream->status === 'live')
                                        <form action="{{ route('admin.live-streams.stop', $stream) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger w-100">
                                                <i class="bi bi-stop-circle"></i> Stop
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-secondary w-100" disabled>
                                            <i class="bi bi-slash-circle"></i> Ended
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $streams->links() }}
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="bi bi-camera-video fa-3x mb-3 d-block text-muted"></i>
            <h5>No Live Streams Yet</h5>
            <p class="text-muted mb-0">Facebook বা YouTube লিংক দিয়ে আপনার প্রথম লাইভ স্ট্রিম যোগ করুন!</p>
        </div>
    @endif
</div>

<style>
    .animate-pulse {
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .hover-shadow {
        transition: box-shadow 0.3s ease;
    }

    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endsection
