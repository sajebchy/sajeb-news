@extends('layouts.admin')

@section('page-title', isset($tag) ? 'Edit Tag' : 'Create Tag')

@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="table-wrapper">
            <form method="POST" action="{{ isset($tag) ? route('admin.tags.update', $tag) : route('admin.tags.store') }}">
                @csrf
                @if (isset($tag))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Tag Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $tag->name ?? '') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $tag->slug ?? '') }}">
                    <small class="text-muted">Leave empty to auto-generate</small>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="color" class="form-label">Color</label>
                    <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color', $tag->color ?? '#667eea') }}">
                    @error('color')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $tag->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ isset($tag) ? 'Update' : 'Create' }} Tag
                    </button>
                    <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
