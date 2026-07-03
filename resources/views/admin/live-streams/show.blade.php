@extends('layouts.admin')

@section('title', $stream->title)

@section('content')
@php $embedSrc = $stream->getEmbedSrc(); @endphp
<div class="container-fluid py-3 py-md-4">

    {{-- ─── Page Header ─── --}}
    <div class="d-flex flex-column flex-md-row md:align-items-center justify-content-between gap-3 mb-4">
        <div class="min-w-0">
            <h2 class="h4 h-md-3 mb-1 text-truncate">{{ $stream->title }}</h2>
            <div class="text-muted small">
                <i class="bi bi-calendar3"></i>
                Created {{ $stream->created_at ? $stream->created_at->diffForHumans() : 'Recently' }}
                @if($stream->status === 'live')
                    <span class="badge bg-danger ms-2"><span class="live-dot"></span> LIVE</span>
                @elseif($stream->status === 'pending')
                    <span class="badge bg-warning text-dark ms-2"><i class="bi bi-clock"></i> SCHEDULED</span>
                @elseif($stream->status === 'ended')
                    <span class="badge bg-secondary ms-2"><i class="bi bi-check-circle"></i> ENDED</span>
                @else
                    <span class="badge bg-info ms-2"><i class="bi bi-file-earmark"></i> DRAFT</span>
                @endif
            </div>
        </div>
        <div class="d-flex gap-2 flex-shrink-0">
            <a href="{{ route('admin.live-streams.edit', $stream) }}" class="btn btn-outline-primary flex-fill flex-md-grow-0">
                <i class="bi bi-pencil-square"></i> <span class="d-none d-sm-inline">Edit</span>
            </a>
            <a href="{{ route('admin.live-streams.index') }}" class="btn btn-outline-secondary flex-fill flex-md-grow-0">
                <i class="bi bi-arrow-left"></i> <span class="d-none d-sm-inline">Back</span>
            </a>
        </div>
    </div>

    <div class="row g-3 g-lg-4">
        {{-- ═══════════ Main Column ═══════════ --}}
        <div class="col-12 col-lg-8">

            {{-- ─── Stream Preview (live embed) ─── --}}
            <div class="card shadow-sm mb-3 mb-lg-4 overflow-hidden">
                <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fs-6"><i class="bi bi-play-btn"></i> Stream Preview</h5>
                    @if($stream->getEmbedPlatform())
                        <span class="badge bg-light text-dark text-capitalize">
                            <i class="bi {{ $stream->getEmbedPlatform() === 'youtube' ? 'bi-youtube' : 'bi-facebook' }}"></i>
                            {{ $stream->getEmbedPlatform() }}
                        </span>
                    @endif
                </div>
                <div class="card-body p-0 bg-dark">
                    @if($embedSrc)
                        <div class="ratio ratio-16x9">
                            <iframe src="{{ $embedSrc }}"
                                    title="{{ $stream->title }}"
                                    allow="autoplay; encrypted-media; picture-in-picture; fullscreen"
                                    allowfullscreen style="border:0;"></iframe>
                        </div>
                    @elseif($stream->thumbnail)
                        <img src="{{ asset('storage/' . $stream->thumbnail) }}" class="img-fluid w-100"
                             alt="{{ $stream->title }}" style="max-height: 420px; object-fit: cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center text-white-50 ratio ratio-16x9">
                            <div class="text-center">
                                <i class="bi bi-camera-video-off fs-1 d-block mb-2"></i>
                                <p class="mb-0 small">কোনো স্ট্রিম লিংক দেওয়া হয়নি</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ─── Stat Tiles (responsive: 2 cols mobile → 4 cols desktop) ─── --}}
            <div class="row row-cols-2 row-cols-md-4 g-2 g-md-3 mb-3 mb-lg-4">
                <div class="col">
                    <div class="card shadow-sm h-100 text-center border-0 stat-tile">
                        <div class="card-body py-3">
                            <i class="bi bi-eye-fill text-primary fs-4 d-block mb-1"></i>
                            <div class="fs-4 fw-bold lh-1">{{ number_format($stream->view_count ?? 0) }}</div>
                            <div class="text-muted small mt-1">Total Views</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm h-100 text-center border-0 stat-tile">
                        <div class="card-body py-3">
                            <i class="bi bi-people-fill text-success fs-4 d-block mb-1"></i>
                            <div class="fs-4 fw-bold lh-1">{{ number_format($stream->viewer_count ?? 0) }}</div>
                            <div class="text-muted small mt-1">Current Viewers</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm h-100 text-center border-0 stat-tile">
                        <div class="card-body py-3">
                            <i class="bi bi-graph-up-arrow text-warning fs-4 d-block mb-1"></i>
                            <div class="fs-4 fw-bold lh-1">{{ number_format($stream->peak_viewers ?? 0) }}</div>
                            <div class="text-muted small mt-1">Peak Viewers</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm h-100 text-center border-0 stat-tile">
                        <div class="card-body py-3">
                            <i class="bi bi-stopwatch text-info fs-4 d-block mb-1"></i>
                            <div class="fs-4 fw-bold lh-1">{{ $stream->getFormattedDuration() ?: '—' }}</div>
                            <div class="text-muted small mt-1">Duration</div>
                        </div>
                    </div>
                </div>
            </div>

            @if(in_array($stream->getEmbedPlatform(), ['youtube', 'facebook']))
                <div class="alert alert-light border small d-flex gap-2 mb-3 mb-lg-4">
                    <i class="bi bi-info-circle text-info mt-1"></i>
                    <div>এই স্ট্রিমটি {{ ucfirst($stream->getEmbedPlatform()) }} থেকে এমবেড করা। প্রকৃত লাইভ দর্শকসংখ্যা {{ ucfirst($stream->getEmbedPlatform()) }} প্ল্যাটফর্মেই দেখা যায়; উপরের সংখ্যাগুলো এই সাইটের অভ্যন্তরীণ গণনা।</div>
                </div>
            @endif

            {{-- ─── Description ─── --}}
            @if($stream->description)
                <div class="card shadow-sm mb-3 mb-lg-4">
                    <div class="card-header"><h5 class="mb-0 fs-6"><i class="bi bi-text-left"></i> Description</h5></div>
                    <div class="card-body">
                        <p class="text-muted mb-0" style="white-space: pre-line;">{{ $stream->description }}</p>
                    </div>
                </div>
            @endif

            {{-- ─── Stream Information ─── --}}
            <div class="card shadow-sm">
                <div class="card-header"><h5 class="mb-0 fs-6"><i class="bi bi-info-circle"></i> Stream Information</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="text-muted small">Category</label>
                            <div class="fw-semibold">{{ $stream->category ?? 'Not specified' }}</div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="text-muted small">Visibility</label>
                            <div class="fw-semibold text-capitalize">{{ $stream->visibility }}</div>
                        </div>
                    </div>
                    @if($stream->stream_tags)
                        <hr>
                        <label class="text-muted small d-block mb-1">Tags</label>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($stream->stream_tags as $tag)
                                <span class="badge bg-secondary">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ═══════════ Sidebar ═══════════ --}}
        <div class="col-12 col-lg-4">

            {{-- Stream Source --}}
            <div class="card shadow-sm mb-3 mb-lg-4 border-info">
                <div class="card-header bg-info text-white"><h5 class="mb-0 fs-6"><i class="bi bi-link-45deg"></i> Stream Source</h5></div>
                <div class="card-body">
                    <label class="form-label small text-muted">Facebook / YouTube Embed Link</label>
                    <input type="text" class="form-control form-control-sm font-monospace" value="{{ $stream->embed_url }}" readonly>
                    <small class="text-muted d-block mt-1">
                        @if($stream->getEmbedPlatform())
                            Source: <span class="text-capitalize fw-bold">{{ $stream->getEmbedPlatform() }}</span>
                        @else
                            <span class="text-danger">অসমর্থিত বা খালি লিংক</span>
                        @endif
                    </small>
                    <a href="{{ route('admin.live-streams.edit', $stream) }}" class="btn btn-sm btn-outline-info w-100 mt-2">
                        <i class="bi bi-pencil"></i> লিংক পরিবর্তন করুন
                    </a>
                </div>
            </div>

            {{-- Stream Controls --}}
            <div class="card shadow-sm mb-3 mb-lg-4">
                <div class="card-header"><h5 class="mb-0 fs-6"><i class="bi bi-sliders"></i> Stream Control</h5></div>
                <div class="card-body">
                    @if($stream->status === 'draft' || $stream->status === 'pending')
                        <form action="{{ route('admin.live-streams.start', $stream) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100"><i class="bi bi-broadcast"></i> Start Stream</button>
                        </form>
                    @elseif($stream->status === 'live')
                        <div class="alert alert-danger py-2 mb-2 small"><i class="bi bi-exclamation-circle"></i> Stream is currently LIVE</div>
                        <form action="{{ route('admin.live-streams.stop', $stream) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100"><i class="bi bi-stop-circle"></i> Stop Stream</button>
                        </form>
                    @else
                        <button class="btn btn-secondary w-100 mb-2" disabled><i class="bi bi-slash-circle"></i> Stream Ended</button>
                    @endif

                    <form action="{{ route('admin.live-streams.toggle-featured', $stream) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning w-100">
                            <i class="bi bi-star{{ $stream->is_featured ? '-fill' : '' }}"></i>
                            {{ $stream->is_featured ? 'Remove from Featured' : 'Add to Featured' }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Timing --}}
            @if($stream->started_at)
                <div class="card shadow-sm mb-3 mb-lg-4">
                    <div class="card-header"><h5 class="mb-0 fs-6"><i class="bi bi-hourglass-split"></i> Timing</h5></div>
                    <div class="card-body small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Started</span>
                            <span class="fw-semibold text-end">{{ $stream->started_at->format('M d, Y H:i') }}</span>
                        </div>
                        @if($stream->ended_at)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Ended</span>
                                <span class="fw-semibold text-end">{{ $stream->ended_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Duration</span>
                                <span class="fw-bold text-end">{{ $stream->getFormattedDuration() }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Share --}}
            @if($stream->status !== 'draft')
                <div class="card shadow-sm mb-3 mb-lg-4">
                    <div class="card-header"><h5 class="mb-0 fs-6"><i class="bi bi-share"></i> Share Stream</h5></div>
                    <div class="card-body">
                        <div class="input-group input-group-sm mb-2">
                            <input type="text" id="share-url-input" class="form-control" value="{{ route('live.watch', $stream->slug) }}" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyShareUrl()"><i class="bi bi-clipboard"></i></button>
                        </div>
                        <a href="{{ route('live.watch', $stream->slug) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                            <i class="bi bi-box-arrow-up-right"></i> ওয়েবসাইটে দেখুন
                        </a>
                    </div>
                </div>
            @endif

            {{-- Delete --}}
            @if($stream->status !== 'live')
                <form action="{{ route('admin.live-streams.destroy', $stream) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this stream?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100"><i class="bi bi-trash"></i> Delete Stream</button>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
    function copyShareUrl() {
        const input = document.getElementById('share-url-input');
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value).then(() => {
            alert('Stream URL copied to clipboard!');
        }).catch(() => {
            document.execCommand('copy');
            alert('Stream URL copied to clipboard!');
        });
    }
</script>

<style>
    .stat-tile { background: #fff; transition: transform .15s ease, box-shadow .15s ease; }
    .stat-tile:hover { transform: translateY(-2px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important; }
    .font-monospace { font-family: 'Courier New', monospace; }
    .live-dot {
        display: inline-block; width: 7px; height: 7px; border-radius: 50%;
        background: #fff; margin-right: 3px; animation: pulse 1s infinite;
    }
    @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: .35; } }
    @media (max-width: 575.98px) {
        .stat-tile .fs-4 { font-size: 1.15rem !important; }
    }
</style>
@endsection
