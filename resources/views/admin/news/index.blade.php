@extends('layouts.admin')

@section('page-title', 'News Posts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5><i class="bi bi-file-text"></i> News Management</h5>
    <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> Add New Post
    </a>
</div>

<!-- Search and Filter -->
<div class="table-wrapper mb-4">
    <form method="GET" class="row g-2 g-md-3 mb-3">
        <div class="col-12 col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by title..." value="{{ request('search') }}">
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                <option value="published" @selected(request('status') === 'published')>Published</option>
                <option value="scheduled" @selected(request('status') === 'scheduled')>Scheduled</option>
            </select>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                @foreach ($categories ?? [] as $category)
                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-2">
            <button type="submit" class="btn btn-secondary w-100">
                <i class="bi bi-search"></i> Search
            </button>
        </div>
    </form>
</div>

<!-- News Table -->
<div class="table-wrapper">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Author</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($news as $item)
                    <tr>
                        <td>
                            <strong>{{ \Str::limit($item->title, 50) }}</strong>
                            @if ($item->is_featured)
                                <span class="badge bg-warning ms-2"><i class="bi bi-star-fill"></i> Featured</span>
                            @endif
                            @if ($item->is_breaking)
                                <span class="badge bg-danger ms-2"><i class="bi bi-fire"></i> Breaking</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $item->category->name ?? 'N/A' }}</span>
                        </td>
                        <td>
                            @if ($item->status === 'published')
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Published</span>
                            @elseif ($item->status === 'draft')
                                <span class="badge bg-secondary"><i class="bi bi-pencil"></i> Draft</span>
                            @else
                                <span class="badge bg-warning"><i class="bi bi-clock"></i> Scheduled</span>
                            @endif
                        </td>
                        <td>
                            <i class="bi bi-eye"></i> {{ $item->views ?? 0 }}
                        </td>
                        <td>{{ $item->author->name ?? 'Unknown' }}</td>
                        <td>{{ $item->published_at?->format('M d, Y') ?? '-' }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('news.show', $item) }}" target="_blank" class="btn btn-outline-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.news.destroy', $item) }}" style="display: inline;" onsubmit="return confirm('Delete this news?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <p class="text-muted"><i class="bi bi-inbox"></i> No news posts found. <a href="{{ route('admin.news.create') }}">Create one now</a></p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if ($news instanceof \Illuminate\Pagination\Paginator && $news->hasPages())
        <nav class="mt-4">
            {{ $news->links() }}
        </nav>
    @endif
</div>

@endsection
