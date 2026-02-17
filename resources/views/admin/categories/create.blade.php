@extends('layouts.admin')

@section('page-title', isset($category) ? 'Edit Category' : 'Create Category')

@section('content')
<div class="row">
    <div class="col-12 col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-folder"></i> 
                    {{ isset($category) ? 'Edit Category' : 'Create New Category' }}
                </h5>

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
                        <small class="text-muted">Leave empty to auto-generate from name</small>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $category->description ?? '') }}</textarea>
                        <small class="text-muted">Brief description of this category</small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Featured Order Section -->
                    <hr class="my-4">
                    <h6 class="mb-3">
                        <i class="bi bi-star"></i> Homepage Display Settings
                    </h6>

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

                    <!-- ClaimReview Schema Fields -->
                    <hr class="my-4">
                    <h6 class="mb-3">
                        <i class="bi bi-shield-check"></i> Fact-Checking Configuration (Optional)
                    </h6>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="is_fact_checker" name="is_fact_checker" value="1" {{ old('is_fact_checker', $category->is_fact_checker ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_fact_checker">
                                <strong>Mark as Fact-Checker Category</strong>
                                <br><small class="text-muted">Enable ClaimReview Schema for articles in this category</small>
                            </label>
                        </div>
                    </div>

                    <div id="claimReviewFields" style="display: {{ old('is_fact_checker', $category->is_fact_checker ?? false) ? 'block' : 'none' }};">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="claim_review_enabled" name="claim_review_enabled" value="1" {{ old('claim_review_enabled', $category->claim_review_enabled ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="claim_review_enabled">
                                    Enable ClaimReview Schema
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="claim_reviewer_name" class="form-label">Reviewer/Fact-Checker Name</label>
                            <input type="text" class="form-control @error('claim_reviewer_name') is-invalid @enderror" id="claim_reviewer_name" name="claim_reviewer_name" value="{{ old('claim_reviewer_name', $category->claim_reviewer_name ?? '') }}" placeholder="e.g., Sajeb News Fact Check Team">
                            <small class="text-muted">Organization or person responsible for fact-checking</small>
                            @error('claim_reviewer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="claim_reviewer_url" class="form-label">Reviewer URL</label>
                            <input type="url" class="form-control @error('claim_reviewer_url') is-invalid @enderror" id="claim_reviewer_url" name="claim_reviewer_url" value="{{ old('claim_reviewer_url', $category->claim_reviewer_url ?? '') }}" placeholder="https://example.com/fact-checker">
                            <small class="text-muted">Link to fact-checker profile or page</small>
                            @error('claim_reviewer_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="claim_rating_scale" class="form-label">Default Claim Rating Scale</label>
                            <select class="form-control @error('claim_rating_scale') is-invalid @enderror" id="claim_rating_scale" name="claim_rating_scale">
                                <option value="">-- Select Rating Scale --</option>
                                <option value="True" {{ old('claim_rating_scale', $category->claim_rating_scale ?? '') === 'True' ? 'selected' : '' }}>True</option>
                                <option value="Mostly True" {{ old('claim_rating_scale', $category->claim_rating_scale ?? '') === 'Mostly True' ? 'selected' : '' }}>Mostly True</option>
                                <option value="Partly False" {{ old('claim_rating_scale', $category->claim_rating_scale ?? '') === 'Partly False' ? 'selected' : '' }}>Partly False</option>
                                <option value="False" {{ old('claim_rating_scale', $category->claim_rating_scale ?? '') === 'False' ? 'selected' : '' }}>False</option>
                                <option value="Unproven" {{ old('claim_rating_scale', $category->claim_rating_scale ?? '') === 'Unproven' ? 'selected' : '' }}>Unproven</option>
                            </select>
                            <small class="text-muted">Default rating scale for claims in this category</small>
                            @error('claim_rating_scale')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check"></i> {{ isset($category) ? 'Update' : 'Create' }} Category
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isFactCheckerCheckbox = document.getElementById('is_fact_checker');
    const claimReviewFields = document.getElementById('claimReviewFields');

    function toggleClaimReviewFields() {
        if (isFactCheckerCheckbox.checked) {
            claimReviewFields.style.display = 'block';
        } else {
            claimReviewFields.style.display = 'none';
        }
    }

    isFactCheckerCheckbox.addEventListener('change', toggleClaimReviewFields);
});
</script>
@endsection
