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
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-film"></i> <strong>ইমেজ আপলোড:</strong> টুলবারে ইমেজ আইকন ক্লিক করুন এবং ফাইল নির্বাচন করুন (Max 5MB)। সাইজ ডায়ালগ এ প্রস্থ (px) দিন অথবা auto হতে দিন।
                                <br>
                                <i class="bi bi-play-circle"></i> <strong>ভিডিও এম্বেড:</strong> টুলবারে ভিডিও আইকন ক্লিক করুন এবং YouTube/Vimeo লিঙ্ক পেস্ট করুন। প্রস্থ (px) দিন বা ডিফল্ট ৬০০px ব্যবহার করুন।
                            </small>
                            @error('content')
                                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
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

                        <!-- Fact-Check Configuration -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_claim_review" name="is_claim_review" value="1" @checked(old('is_claim_review', $news->is_claim_review ?? false)) onchange="toggleClaimReviewFields()">
                                <label class="form-check-label" for="is_claim_review">
                                    <i class="fas fa-check-double"></i> Mark as Fact-Check Article
                                </label>
                            </div>
                        </div>

                        <!-- Claim Fields (shown when is_claim_review is checked) -->
                        <div id="claim-review-section" class="p-3 border border-info rounded bg-light" style="display: none;">
                            <h6 class="mb-3 text-info">
                                <i class="fas fa-shield-alt"></i> Fact-Check Configuration
                            </h6>

                            <!-- Claim Being Reviewed -->
                            <div class="mb-3">
                                <label for="claim_being_reviewed" class="form-label">Claim Being Reviewed *</label>
                                <textarea class="form-control @error('claim_being_reviewed') is-invalid @enderror" id="claim_being_reviewed" name="claim_being_reviewed" rows="2" placeholder="Enter the claim being fact-checked">{{ old('claim_being_reviewed', $news->claim_being_reviewed ?? '') }}</textarea>
                                <small class="text-muted">The exact claim that is being reviewed</small>
                                @error('claim_being_reviewed')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Claim Rating -->
                            <div class="mb-3">
                                <label for="claim_rating" class="form-label">Claim Rating *</label>
                                <select class="form-select @error('claim_rating') is-invalid @enderror" id="claim_rating" name="claim_rating">
                                    <option value="">Select Rating</option>
                                    <option value="True" @selected(old('claim_rating', $news->claim_rating ?? '') == 'True')>✓ True</option>
                                    <option value="Mostly True" @selected(old('claim_rating', $news->claim_rating ?? '') == 'Mostly True')>≈ Mostly True</option>
                                    <option value="Partly False" @selected(old('claim_rating', $news->claim_rating ?? '') == 'Partly False')>⚠ Partly False</option>
                                    <option value="False" @selected(old('claim_rating', $news->claim_rating ?? '') == 'False')>✗ False</option>
                                    <option value="Unproven" @selected(old('claim_rating', $news->claim_rating ?? '') == 'Unproven')>? Unproven</option>
                                </select>
                                <small class="text-muted">Verdict of the fact-check</small>
                                @error('claim_rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fact-Check Evidence -->
                            <div class="mb-3">
                                <label for="claim_review_evidence" class="form-label">Fact-Check Evidence & Explanation *</label>
                                <textarea class="form-control @error('claim_review_evidence') is-invalid @enderror" id="claim_review_evidence" name="claim_review_evidence" rows="4" placeholder="Enter detailed evidence and explanation for the fact-check">{{ old('claim_review_evidence', $news->claim_review_evidence ?? '') }}</textarea>
                                <small class="text-muted">Detailed analysis, sources, and explanation</small>
                                @error('claim_review_evidence')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Review Date -->
                            <div class="mb-3">
                                <label for="claim_review_date" class="form-label">Review Date *</label>
                                <input type="datetime-local" class="form-control @error('claim_review_date') is-invalid @enderror" id="claim_review_date" name="claim_review_date" value="{{ old('claim_review_date', $news->claim_review_date?->format('Y-m-d H:i') ?? '') }}">
                                <small class="text-muted">When the fact-check was published</small>
                                @error('claim_review_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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

    /* Responsive images and videos in editor */
    #editor-container img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 10px 0;
        cursor: pointer;
        transition: box-shadow 0.3s ease;
    }

    #editor-container img:hover {
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
    }

    #editor-container iframe {
        border-radius: 8px;
        margin: 10px 0;
    }

    .resize-hint {
        font-size: 11px;
        color: #666;
        font-style: italic;
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

    // Setup image resize on double-click
    setupImageResizeFeature();

    // Handle image uploads
    const imageHandler = () => {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/jpeg, image/png, image/gif, image/webp');
        input.click();

        input.onchange = async () => {
            const file = input.files[0];
            if (file && file.size <= 5 * 1024 * 1024) { // 5MB max
                const formData = new FormData();
                formData.append('file', file);

                try {
                    const response = await fetch('{{ route("admin.news.upload-image") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: formData
                    });

                    const data = await response.json();
                    if (data.location) {
                        // Show size dialog
                        showImageSizeDialog(data.location);
                    } else {
                        alert('ছবি আপলোড ব্যর্থ হয়েছে। (Image upload failed.)');
                    }
                } catch (error) {
                    console.error('Upload error:', error);
                    alert('আপলোড করতে একটি ত্রুটি ঘটেছে। (An error occurred during upload.)');
                }
            } else {
                alert('ছবির আকার 5MB এর কম হতে হবে। (Image size must be less than 5MB.)');
            }
        };
    };

    // Show image size input dialog
    function showImageSizeDialog(imageUrl) {
        const width = prompt('ছবির প্রস্থ (Width in px) - খালি থাকলে স্বয়ংক্রিয় হবে:', '600');
        
        const range = quill.getSelection();
        const index = range ? range.index : quill.getLength();
        
        if (width && !isNaN(width)) {
            // Insert image with style
            quill.insertEmbed(index, 'image', imageUrl);
            // Apply width style to the image
            const imageElement = quill.root.querySelector(`img[src="${imageUrl}"]`);
            if (imageElement) {
                imageElement.style.maxWidth = width + 'px';
                imageElement.style.height = 'auto';
                imageElement.style.cursor = 'pointer';
                imageElement.title = 'ডাবল ক্লিক করুন সাইজ পরিবর্তনের জন্য (Double-click to resize)';
            }
        } else {
            // Insert image with default size
            quill.insertEmbed(index, 'image', imageUrl);
            const imageElement = quill.root.querySelector(`img[src="${imageUrl}"]`);
            if (imageElement) {
                imageElement.style.maxWidth = '100%';
                imageElement.style.height = 'auto';
                imageElement.style.cursor = 'pointer';
                imageElement.title = 'ডাবল ক্লিক করুন সাইজ পরিবর্তনের জন্য (Double-click to resize)';
            }
        }
    };

    // Setup double-click image resize feature
    function setupImageResizeFeature() {
        const editorContainer = document.querySelector('.ql-editor');
        if (!editorContainer) return;
        
        editorContainer.addEventListener('dblclick', function(e) {
            if (e.target.tagName === 'IMG') {
                e.preventDefault();
                const img = e.target;
                const currentWidth = img.style.maxWidth || img.currentWidth + 'px';
                const newWidth = prompt('নতুন প্রস্থ (New width in px) বা "auto" লিখুন:', currentWidth);
                
                if (newWidth !== null) {
                    if (newWidth.toLowerCase() === 'auto') {
                        img.style.maxWidth = '100%';
                    } else if (!isNaN(newWidth)) {
                        img.style.maxWidth = newWidth + 'px';
                    } else {
                        alert('দয়া করে একটি সংখ্যা লিখুন অথবা "auto" লিখুন। (Please enter a number or "auto".)');
                    }
                }
            }
        });
    };

    // Handle video embeds with size options
    const videoHandler = () => {
        const url = prompt('ভিডিও URL প্রবেশ করুন (Enter video URL):\nসমর্থিত: YouTube, Vimeo, etc.');
        if (url) {
            try {
                new URL(url);
                
                // Show video size dialog
                const width = prompt('ভিডিওর প্রস্থ (Width in px) - খালি থাকলে 600px হবে:', '600');
                
                const range = quill.getSelection();
                const index = range ? range.index : quill.getLength();
                
                quill.insertEmbed(index, 'video', url);
                
                // Apply width to iframe if it's embedded
                const iframes = quill.root.querySelectorAll('iframe');
                if (iframes.length > 0) {
                    const lastIframe = iframes[iframes.length - 1];
                    const videoWidth = (width && !isNaN(width)) ? width : '600';
                    lastIframe.style.maxWidth = videoWidth + 'px';
                    lastIframe.style.width = '100%';
                    lastIframe.style.height = 'auto';
                    lastIframe.setAttribute('style', lastIframe.getAttribute('style') + '; aspect-ratio: 16/9;');
                }
            } catch (e) {
                alert('অবৈধ URL। (Invalid URL.)');
            }
        }
    };

    // Override default image handler
    const toolbar = quill.getModule('toolbar');
    toolbar.addHandler('image', imageHandler);
    toolbar.addHandler('video', videoHandler);

    // Setup double-click image resize
    setupImageResizeFeature();

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

    function toggleClaimReviewFields() {
        const isClaimReview = document.getElementById('is_claim_review').checked;
        const claimSection = document.getElementById('claim-review-section');
        const claimFields = claimSection.querySelectorAll('input[name^="claim_"], textarea[name^="claim_"]');
        
        if (isClaimReview) {
            claimSection.style.display = 'block';
            claimFields.forEach(field => {
                if (field.name !== 'claim_review_date') {
                    field.setAttribute('required', 'required');
                }
            });
        } else {
            claimSection.style.display = 'none';
            claimFields.forEach(field => {
                field.removeAttribute('required');
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleClaimReviewFields();
    });
</script>
@endsection
