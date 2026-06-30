@extends('layouts.admin')

@section('page-title', $news->id ? 'Edit News' : 'Create News')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="table-wrapper">
            <form method="POST" action="{{ $news->id ? route('admin.news.update', $news) : route('admin.news.store') }}" enctype="multipart/form-data">
                @csrf
                @if ($news->id)
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-12 col-lg-8">
                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $news->title ?? '') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $news->slug ?? '') }}">
                            <small class="text-muted">Leave empty to auto-generate</small>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Excerpt -->
                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Excerpt</label>
                            <textarea class="form-control @error('excerpt') is-invalid @enderror" id="excerpt" name="excerpt" rows="3" maxlength="500">{{ old('excerpt', $news->excerpt ?? '') }}</textarea>
                            <small class="text-muted">Brief summary of the news (max 500 characters)</small>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Content with Quill Rich Text Editor -->
                        <div class="mb-3">
                            <label for="editor-container" class="form-label">Content *</label>
                            <div id="editor-container" style="height: 400px; background-color: white; border: 1px solid #dee2e6; border-radius: 0.375rem;"></div>
                            <textarea id="content" name="content" style="display:none;">{{ old('content', $news->content ?? '') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Topics -->
                        <div class="mb-3">
                            <label for="tags" class="form-label">Topics</label>
                            <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags" value="{{ old('tags', $news->tags->pluck('name')->implode(', ') ?? '') }}">
                            <small class="text-muted">Separate topics with commas</small>
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category *</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $news->category_id ?? '') == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Featured Image -->
                        <div class="mb-3">
                            <label for="featured_image" class="form-label">Featured Image</label>
                            <input type="file" class="form-control @error('featured_image') is-invalid @enderror" id="featured_image" name="featured_image" accept="image/*">
                            <small class="text-muted">Max 5MB</small>
                            @if (isset($news) && $news->featured_image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $news->featured_image) }}" alt="Featured" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            @endif
                            @error('featured_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="draft" @selected(old('status', $news->status ?? '') == 'draft')>Draft</option>
                                <option value="published" @selected(old('status', $news->status ?? '') == 'published')>Published</option>
                                <option value="scheduled" @selected(old('status', $news->status ?? '') == 'scheduled')>Scheduled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Publish Date -->
                        <div class="mb-3">
                            <label for="published_at" class="form-label">Publish Date</label>
                            <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at', $news->published_at?->format('Y-m-d H:i') ?? '') }}">
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Featured -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $news->is_featured ?? false))>
                                <label class="form-check-label" for="is_featured">
                                    <i class="fas fa-star"></i> Featured News
                                </label>
                            </div>
                        </div>

                        <!-- Breaking News -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_breaking" name="is_breaking" value="1" @checked(old('is_breaking', $news->is_breaking ?? false))>
                                <label class="form-check-label" for="is_breaking">
                                    <i class="fas fa-fire"></i> Breaking News
                                </label>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ isset($news) ? 'Update' : 'Create' }} Post
                            </button>
                            <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.snow.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@400;500;700&display=swap" rel="stylesheet">

<style>
    #title {
        font-family: 'Noto Serif Bengali', serif;
        font-weight: 400;
    }
    
    #excerpt {
        font-family: 'Noto Serif Bengali', serif;
        font-weight: 400;
    }
    
    #editor-container {
        font-family: 'Noto Serif Bengali', serif;
    }
    
    .ql-font-serif-bengali {
        font-family: 'Noto Serif Bengali', serif;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.js"></script>

<script>
    // Register only Noto Serif Bengali font in Quill
    const Font = Quill.import('formats/font');
    Font.whitelist = ['serif-bengali'];
    Quill.register(Font, true);

    // Initialize Quill Editor with Noto Serif Bengali
    const quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'শুরু করুন লেখা... (Start writing...)',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'header': 1 }, { 'header': 2 }, { 'header': 3 }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'size': ['small', false, 'large', 'huge'] }],
                [{ 'header': [false, 1, 2, 3, 4, 5, 6] }],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'font': ['serif-bengali'] }],
                [{ 'align': [] }],
                ['link', 'image', 'video'],
                ['clean'],
                ['undo', 'redo']
            ]
        }
    });

    // Set default font to Noto Serif Bengali
    quill.format('font', 'serif-bengali');

    // Load existing content if available
    const existingContent = document.querySelector('textarea[name="content"]').value;
    if (existingContent) {
        quill.root.innerHTML = existingContent;
    }

    // Sync Quill content to hidden textarea on text changes
    quill.on('text-change', function(delta, oldDelta, source) {
        const contentTextarea = document.querySelector('textarea[name="content"]');
        if (contentTextarea) {
            contentTextarea.value = quill.root.innerHTML;
        }
    });

    // Handle form submission - ensure content is copied from Quill
    (function() {
        const form = document.querySelector('form');
        if (form) {
            // Add onsubmit handler as fallback
            form.onsubmit = function(e) {
                // Get content from Quill and copy to hidden textarea
                const content = quill.root.innerHTML;
                const contentTextarea = document.querySelector('textarea[name="content"]');
                
                if (contentTextarea) {
                    contentTextarea.value = content;
                }
                
                // Validate that content is not empty
                if (!quill.getText().trim()) {
                    e.preventDefault();
                    alert('সামগ্রী খালি থাকতে পারে না। (Content cannot be empty.)');
                    return false;
                }
                return true;
            };
        }
    })();
</script>
@endsection
