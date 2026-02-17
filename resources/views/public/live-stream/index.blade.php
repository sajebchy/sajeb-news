@extends('layouts.app')

@section('title', 'Live Streams')

@section('content')
<div class="container-fluid mt-4">
    <div class="mb-4">
        <h2 class="mb-2"><i class="fas fa-video"></i> Live Streams</h2>
        <p class="text-muted">Watch live broadcasts and scheduled streams</p>
    </div>

    {{-- Featured Streams --}}
    @if($featured_streams->count())
        <section class="mb-5">
            <h4 class="mb-3"><i class="fas fa-star"></i> Featured Streams</h4>
            <div class="row">
                @foreach($featured_streams as $stream)
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow overflow-hidden hover-card">
                            <div class="position-relative">
                                @if($stream->thumbnail)
                                    <img src="{{ asset('storage/' . $stream->thumbnail) }}" class="card-img-top" alt="{{ $stream->title }}" style="height: 250px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 250px;">
                                        <i class="fas fa-video fa-4x"></i>
                                    </div>
                                @endif

                                {{-- Status Badge --}}
                                <span class="position-absolute top-0 start-0 mt-2 ms-2">
                                    @if($stream->status === 'live')
                                        <span class="badge bg-danger"><i class="fas fa-circle animate-pulse"></i> LIVE</span>
                                    @elseif($stream->status === 'pending')
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> SOON</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="fas fa-check"></i> ENDED</span>
                                    @endif
                                </span>
                            </div>

                            <div class="card-body">
                                <a href="{{ route('live.watch', $stream->slug) }}" class="text-decoration-none">
                                    <h5 class="card-title mb-2">{{ Str::limit($stream->title, 50) }}</h5>
                                </a>

                                <div class="row text-center small mb-3">
                                    <div class="col">
                                        <div class="text-muted">Views</div>
                                        <div class="fw-bold">{{ number_format($stream->view_count) }}</div>
                                    </div>
                                    <div class="col">
                                        <div class="text-muted">Watching</div>
                                        <div class="fw-bold">{{ number_format($stream->viewer_count) }}</div>
                                    </div>
                                    <div class="col">
                                        <div class="text-muted">Duration</div>
                                        <div class="fw-bold">{{ $stream->getFormattedDuration() ?: '-' }}</div>
                                    </div>
                                </div>

                                <a href="{{ route('live.watch', $stream->slug) }}" class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-play-circle"></i> Watch Now
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Live Now Streams --}}
    @if($live_streams->count())
        <section class="mb-5">
            <h4 class="mb-3"><i class="fas fa-broadcast-tower"></i> Now Live</h4>
            <div class="row">
                @foreach($live_streams as $stream)
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card shadow overflow-hidden hover-card h-100">
                            <div class="position-relative">
                                @if($stream->thumbnail)
                                    <img src="{{ asset('storage/' . $stream->thumbnail) }}" class="card-img-top" alt="{{ $stream->title }}" style="height: 180px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 180px;">
                                        <i class="fas fa-video fa-3x"></i>
                                    </div>
                                @endif

                                <span class="position-absolute top-0 start-0 mt-2 ms-2">
                                    <span class="badge bg-danger"><i class="fas fa-circle animate-pulse"></i> LIVE</span>
                                </span>

                                @if($stream->viewer_count > 0)
                                    <span class="position-absolute bottom-0 start-0 mb-2 ms-2">
                                        <span class="badge bg-dark">{{ number_format($stream->viewer_count) }} watching</span>
                                    </span>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column">
                                <a href="{{ route('live.watch', $stream->slug) }}" class="text-decoration-none">
                                    <h6 class="card-title">{{ Str::limit($stream->title, 40) }}</h6>
                                </a>

                                <div class="text-muted small mb-3 flex-grow-1">
                                    @if($stream->user->avatar)
                                        <img src="{{ asset('storage/' . $stream->user->avatar) }}" alt="{{ $stream->user->name }}" class="rounded-circle me-1" style="width: 24px; height: 24px; object-fit: cover;">
                                    @endif
                                    {{ Str::limit($stream->user->name, 20) }}
                                </div>

                                <a href="{{ route('live.watch', $stream->slug) }}" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-play-circle"></i> Watch
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($live_streams->hasMorePages())
                <div class="text-center">
                    <a href="{{ $live_streams->nextPageUrl() }}" class="btn btn-outline-primary">Load More</a>
                </div>
            @endif
        </section>
    @else
        @if($featured_streams->count() === 0 && $upcoming_streams->count() === 0)
            <div class="alert alert-info text-center py-5 mb-5">
                <i class="fas fa-video fa-3x mb-3 d-block text-muted"></i>
                <h5>No Live Streams Currently</h5>
                <p class="text-muted mb-0">Check back soon for upcoming streams!</p>
            </div>
        @endif
    @endif

    {{-- Upcoming Streams --}}
    @if($upcoming_streams->count())
        <section class="mb-5">
            <h4 class="mb-3"><i class="fas fa-calendar-alt"></i> Upcoming Streams</h4>
            <div class="row">
                @foreach($upcoming_streams as $stream)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow overflow-hidden">
                            <div class="row g-0">
                                <div class="col-md-4 position-relative" style="height: 150px;">
                                    @if($stream->thumbnail)
                                        <img src="{{ asset('storage/' . $stream->thumbnail) }}" class="w-100 h-100" alt="{{ $stream->title }}" style="object-fit: cover;">
                                    @else
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center h-100">
                                            <i class="fas fa-video fa-2x"></i>
                                        </div>
                                    @endif

                                    <span class="position-absolute bottom-0 start-0 mb-2 ms-2">
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Scheduled</span>
                                    </span>
                                </div>

                                <div class="col-md-8">
                                    <div class="card-body d-flex flex-column h-100">
                                        <a href="{{ route('live.watch', $stream->slug) }}" class="text-decoration-none">
                                            <h6 class="card-title mb-2">{{ Str::limit($stream->title, 40) }}</h6>
                                        </a>

                                        <div class="text-muted small mb-3">
                                            <i class="fas fa-calendar"></i> {{ $stream->scheduled_at->format('M d, Y') }}<br>
                                            <i class="fas fa-clock"></i> {{ $stream->scheduled_at->format('H:i') }}
                                        </div>

                                        <a href="{{ route('live.watch', $stream->slug) }}" class="btn btn-sm btn-outline-warning mt-auto">
                                            <i class="fas fa-bell"></i> Notify Me
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Search & Filter --}}
    @if($live_streams->count() > 3)
        <section class="mb-5">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="mb-3">Search Streams</h5>
                    <form action="{{ route('live.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Search streams..." value="{{ request('q') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
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

    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endsection
