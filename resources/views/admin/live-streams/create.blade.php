@extends('layouts.admin')

@section('title', isset($stream) && $stream->id ? 'Edit Live Stream' : 'Create Live Stream')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-0">
                <i class="fas fa-video"></i> 
                {{ isset($stream) && $stream->id ? 'Edit Live Stream' : 'Create Live Stream' }}
            </h2>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body p-4">
            <form action="{{ isset($stream) && $stream->id ? route('admin.live-streams.update', $stream) : route('admin.live-streams.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($stream) && $stream->id)
                    @method('PUT')
                @endif

                <div class="row">
                    {{-- Title --}}
                    <div class="col-md-8 mb-3">
                        <label for="title" class="form-label">Stream Title *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $stream->title ?? '') }}" required>
                        <small class="form-text text-muted">Enter an engaging title for your live stream</small>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Category --}}
                    <div class="col-md-4 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category">
                            <option value="">Select Category</option>
                            <option value="News" @selected(old('category', $stream->category ?? '') === 'News')>News</option>
                            <option value="Education" @selected(old('category', $stream->category ?? '') === 'Education')>Education</option>
                            <option value="Entertainment" @selected(old('category', $stream->category ?? '') === 'Entertainment')>Entertainment</option>
                            <option value="Technology" @selected(old('category', $stream->category ?? '') === 'Technology')>Technology</option>
                            <option value="Sports" @selected(old('category', $stream->category ?? '') === 'Sports')>Sports</option>
                            <option value="Other" @selected(old('category', $stream->category ?? '') === 'Other')>Other</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Enter stream description...">{{ old('description', $stream->description ?? '') }}</textarea>
                    <small class="form-text text-muted">Describe what your stream is about</small>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    {{-- Thumbnail --}}
                    <div class="col-md-6 mb-3">
                        <label for="thumbnail" class="form-label">Stream Thumbnail</label>
                        <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail" accept="image/*">
                        <small class="form-text text-muted">Max 5MB (JPG, PNG, WebP)</small>
                        
                        @if(isset($stream) && $stream->thumbnail)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $stream->thumbnail) }}" alt="Thumbnail" class="img-thumbnail" style="max-width: 200px;">
                                <br><small class="text-muted">Current thumbnail</small>
                            </div>
                        @endif
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tags --}}
                    <div class="col-md-6 mb-3">
                        <label for="stream_tags" class="form-label">Tags</label>
                        <input type="text" class="form-control @error('stream_tags') is-invalid @enderror" id="stream_tags" name="stream_tags" value="{{ old('stream_tags', isset($stream) && $stream->stream_tags ? implode(', ', $stream->stream_tags) : '') }}" placeholder="tag1, tag2, tag3">
                        <small class="form-text text-muted">Separate tags with commas</small>
                        @error('stream_tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Visibility --}}
                    <div class="col-md-6 mb-3">
                        <label for="visibility" class="form-label">Visibility *</label>
                        <select class="form-select @error('visibility') is-invalid @enderror" id="visibility" name="visibility" required>
                            <option value="public" @selected(old('visibility', $stream->visibility ?? 'public') === 'public')>Public</option>
                            <option value="unlisted" @selected(old('visibility', $stream->visibility ?? '') === 'unlisted')>Unlisted</option>
                            <option value="private" @selected(old('visibility', $stream->visibility ?? '') === 'private')>Private</option>
                        </select>
                        <small class="form-text text-muted">Who can see this stream?</small>
                        @error('visibility')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Schedule --}}
                    <div class="col-md-6 mb-3">
                        <label for="scheduled_at" class="form-label">Schedule Stream</label>
                        <input type="datetime-local" class="form-control @error('scheduled_at') is-invalid @enderror" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at', isset($stream) && $stream->scheduled_at ? $stream->scheduled_at->format('Y-m-d H:i') : '') }}">
                        <small class="form-text text-muted">Leave empty to start immediately</small>
                        @error('scheduled_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Settings --}}
                <div class="mb-4">
                    <h5 class="mb-3">Stream Settings</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="allow_comments" name="allow_comments" value="1" @checked(old('allow_comments', $stream->allow_comments ?? true))>
                                <label class="form-check-label" for="allow_comments">
                                    <i class="fas fa-comments"></i> Allow Comments After Stream
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="allow_chat" name="allow_chat" value="1" @checked(old('allow_chat', $stream->allow_chat ?? true))>
                                <label class="form-check-label" for="allow_chat">
                                    <i class="fas fa-comment-dots"></i> Enable Live Chat
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('admin.live-streams.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 
                        {{ isset($stream) && $stream->id ? 'Update Stream' : 'Create Stream' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Info Box --}}
    <div class="alert alert-info mt-4" role="alert">
        <h5 class="alert-heading"><i class="fas fa-info-circle"></i> Broadcasting Instructions</h5>
        <p class="mb-0">
            After creating your stream, you'll get a unique <strong>Stream Key</strong> that you'll use with OBS Studio or similar streaming software. 
            The stream will be accessible at <code class="bg-light p-2 rounded">{{ url('/live/{slug}') }}</code>
        </p>
    </div>
</div>
@endsection
