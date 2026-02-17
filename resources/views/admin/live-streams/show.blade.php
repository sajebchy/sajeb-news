@extends('layouts.admin')

@section('title', $stream->title)

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-2">{{ $stream->title }}</h2>
            <div class="text-muted small">
                <i class="fas fa-calendar"></i> Created {{ $stream->created_at ? $stream->created_at->diffForHumans() : 'Recently' }}
            </div>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.live-streams.edit', $stream) }}" class="btn btn-outline-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.live-streams.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">
            {{-- Stream Preview --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-video"></i> Stream Preview</h5>
                </div>
                <div class="card-body p-0">
                    @if($stream->thumbnail)
                        <img src="{{ asset('storage/' . $stream->thumbnail) }}" class="img-fluid w-100" alt="{{ $stream->title }}" style="max-height: 400px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 300px;">
                            <div class="text-center">
                                <i class="fas fa-video fa-4x mb-2"></i>
                                <p>No thumbnail uploaded</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Status Bar --}}
                <div class="card-footer bg-light">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="mb-1">Status</div>
                            @if($stream->status === 'live')
                                <span class="badge bg-danger"><i class="fas fa-circle animate-pulse"></i> LIVE</span>
                            @elseif($stream->status === 'pending')
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> SCHEDULED</span>
                            @elseif($stream->status === 'ended')
                                <span class="badge bg-secondary"><i class="fas fa-check"></i> ENDED</span>
                            @else
                                <span class="badge bg-info"><i class="fas fa-file-alt"></i> DRAFT</span>
                            @endif
                        </div>
                        <div class="col-4">
                            <div class="mb-1">Views</div>
                            <div class="fw-bold">{{ number_format($stream->view_count) }}</div>
                        </div>
                        <div class="col-4">
                            <div class="mb-1">Duration</div>
                            <div class="fw-bold">{{ $stream->getFormattedDuration() ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            @if($stream->description)
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-align-left"></i> Description</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">{{ $stream->description }}</p>
                    </div>
                </div>
            @endif

            {{-- Stream Info --}}
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Stream Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Category</label>
                                <div class="fw-bold">{{ $stream->category ?? 'Not specified' }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Visibility</label>
                                <div class="fw-bold">{{ ucfirst($stream->visibility) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Current Viewers</label>
                                <div class="fw-bold">{{ number_format($stream->viewer_count) }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Peak Viewers</label>
                                <div class="fw-bold">{{ number_format($stream->peak_viewers) }}</div>
                            </div>
                        </div>
                    </div>

                    @if($stream->stream_tags)
                        <div>
                            <label class="text-muted small">Tags</label>
                            <div>
                                @foreach($stream->stream_tags as $tag)
                                    <span class="badge bg-secondary">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Stream Key & OBS Settings --}}
            <div class="card shadow mb-4 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-broadcast-tower"></i> Broadcasting</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning small mb-3">
                        <i class="fas fa-exclamation-triangle"></i> Keep your stream key private. Do not share it publicly!
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Stream Key</label>
                        <div class="input-group">
                            <input type="password" id="stream-key-input" class="form-control font-monospace" value="{{ $stream->stream_key }}" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="toggleStreamKey()">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <small class="text-muted">Use this key in your broadcasting software</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">RTMP Server URL</label>
                        <div class="input-group">
                            <input type="text" id="rtmp-url-input" class="form-control font-monospace" value="{{ $stream->getRtmpUrl() }}" readonly style="font-size: 0.85rem;">
                            <button class="btn btn-outline-secondary" type="button" onclick="copyRtmpUrl()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <small class="text-muted">Use this as server URL in OBS</small>
                    </div>

                    <a href="{{ route('admin.live-streams.obs-settings', $stream) }}" class="btn btn-sm btn-info w-100 mb-2">
                        <i class="fas fa-cogs"></i> OBS Configuration Guide
                    </a>

                    @if(!$stream->isLive())
                        <form action="{{ route('admin.live-streams.regenerate-key', $stream) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-warning w-100" onclick="return confirm('Regenerate stream key? Current key will no longer work.')">
                                <i class="fas fa-redo"></i> Regenerate Key
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Stream Controls --}}
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sliders-h"></i> Stream Control</h5>
                </div>
                <div class="card-body">
                    @if($stream->status === 'draft' || $stream->status === 'pending')
                        <form action="{{ route('admin.live-streams.start', $stream) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-broadcast-tower"></i> Start Stream
                            </button>
                        </form>
                    @elseif($stream->status === 'live')
                        <div class="alert alert-danger mb-2">
                            <small><i class="fas fa-exclamation-circle"></i> Stream is currently LIVE</small>
                        </div>
                        <form action="{{ route('admin.live-streams.stop', $stream) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-stop-circle"></i> Stop Stream
                            </button>
                        </form>
                    @else
                        <button class="btn btn-secondary w-100" disabled>
                            <i class="fas fa-ban"></i> Stream Ended
                        </button>
                    @endif

                    <hr>

                    {{-- Featured Toggle --}}
                    <form action="{{ route('admin.live-streams.toggle-featured', $stream) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning w-100">
                            <i class="fas fa-star"></i> 
                            {{ $stream->is_featured ? 'Remove from Featured' : 'Add to Featured' }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Timing Info --}}
            @if($stream->started_at)
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-hourglass-end"></i> Timing</h5>
                    </div>
                    <div class="card-body small">
                        <div class="mb-2">
                            <label class="text-muted">Started At</label>
                            <div>{{ $stream->started_at->format('M d, Y H:i:s') }}</div>
                        </div>
                        @if($stream->ended_at)
                            <div class="mb-2">
                                <label class="text-muted">Ended At</label>
                                <div>{{ $stream->ended_at->format('M d, Y H:i:s') }}</div>
                            </div>
                            <div>
                                <label class="text-muted">Duration</label>
                                <div class="fw-bold">{{ $stream->getFormattedDuration() }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Share Stream --}}
            @if($stream->status !== 'draft')
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-share-alt"></i> Share Stream</h5>
                    </div>
                    <div class="card-body">
                        <div class="input-group input-group-sm mb-2">
                            <input type="text" id="share-url-input" class="form-control" value="{{ route('live.watch', $stream->slug) }}" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyShareUrl()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <small class="text-muted">Share this link to let others watch your stream</small>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Delete Button --}}
    <div class="mt-4">
        @if($stream->status !== 'live')
            <form action="{{ route('admin.live-streams.destroy', $stream) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this stream?')">
                    <i class="fas fa-trash"></i> Delete Stream
                </button>
            </form>
        @endif
    </div>
</div>

<script>
    function toggleStreamKey() {
        const input = document.getElementById('stream-key-input');
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    function copyToClipboard() {
        const input = document.getElementById('stream-key-input');
        input.type = 'text';
        input.select();
        document.execCommand('copy');
        alert('Stream key copied to clipboard!');
        input.type = 'password';
    }

    function copyRtmpUrl() {
        const input = document.getElementById('rtmp-url-input');
        input.select();
        document.execCommand('copy');
        alert('RTMP URL copied to clipboard!');
    }

    function copyShareUrl() {
        const input = document.getElementById('share-url-input');
        input.select();
        document.execCommand('copy');
        alert('Stream URL copied to clipboard!');
    }
</script>

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

    .font-monospace {
        font-family: 'Courier New', monospace;
    }
</style>
@endsection
