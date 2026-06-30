@extends('layouts.admin')

@section('page-title', isset($category) ? 'Edit Category' : 'Create Category')

@section('content')
<div class="row">
    <div class="col-12 col-lg-8 offset-lg-2">
        <div class="table-wrapper">
            <form method="POST" action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
                @csrf
                @if (isset($category))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Category Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name ?? '') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $category->slug ?? '') }}">
                    <small class="text-muted">Leave empty to auto-generate</small>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="parent_id" class="form-label">Parent Category</label>
                    <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                        <option value="">None (Top Level)</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id ?? '') == $parent->id)>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $category->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="featured_order" class="form-label">Featured Order (Homepage)</label>
                    <select class="form-control @error('featured_order') is-invalid @enderror" id="featured_order" name="featured_order">
                        <option value="">-- Not Featured (Hide from navbar) --</option>
                        <option value="1" {{ old('featured_order', $category->featured_order ?? '') == 1 ? 'selected' : '' }}>1st Category (First in navbar)</option>
                        <option value="2" {{ old('featured_order', $category->featured_order ?? '') == 2 ? 'selected' : '' }}>2nd Category</option>
                        <option value="3" {{ old('featured_order', $category->featured_order ?? '') == 3 ? 'selected' : '' }}>3rd Category</option>
                        <option value="4" {{ old('featured_order', $category->featured_order ?? '') == 4 ? 'selected' : '' }}>4th Category</option>
                        <option value="5" {{ old('featured_order', $category->featured_order ?? '') == 5 ? 'selected' : '' }}>5th Category</option>
                    </select>
                    <small class="text-muted">Select position to show this category in the navigation bar. Leave empty to hide it and place in "অন্যান্য" menu.</small>
                    @error('featured_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="color" class="form-label">Color</label>
                    <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color', $category->color ?? '#667eea') }}">
                    @error('color')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="icon" class="form-label">Icon (Font Awesome)</label>
                    <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon', $category->icon ?? '') }}" placeholder="e.g., fas fa-newspaper">
                    @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ isset($category) ? 'Update' : 'Create' }} Category
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
