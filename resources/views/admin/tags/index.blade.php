@extends('layouts.admin')

@section('page-title', 'Tags')

@section('content')
{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h5 class="mb-0"><i class="bi bi-tags"></i> Tag Management</h5>
    <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> <span class="d-none d-sm-inline">Add Tag</span><span class="d-sm-none">Add</span>
    </a>
</div>

{{-- Search --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-12 col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search tags..." value="{{ request('search') }}">
            </div>
            <div class="col-6 col-md-3">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
            @if(request('search'))
                <div class="col-6 col-md-3">
                    <a href="{{ route('admin.tags.index') }}" class="btn btn-light w-100">
                        <i class="bi bi-x-lg"></i> Clear
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>

{{-- ═══════ Desktop: table (md and up) ═══════ --}}
<div class="card d-none d-md-block">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th class="text-center">Uses</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tags as $tag)
                    <tr>
                        <td><strong>{{ $tag->name }}</strong></td>
                        <td><code class="bg-light p-1 rounded">{{ $tag->slug }}</code></td>
                        <td class="text-center"><span class="badge bg-info">{{ $tag->news_count ?? 0 }}</span></td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" style="display:inline;" onsubmit="return confirm('Delete this tag?');">
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
                        <td colspan="4" class="text-center py-5">
                            <p class="text-muted mb-0"><i class="bi bi-inbox"></i> No tags found. <a href="{{ route('admin.tags.create') }}">Create one now</a></p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ═══════ Mobile: card list (below md) ═══════ --}}
<div class="d-md-none">
    @forelse ($tags as $tag)
        <div class="card mb-2 shadow-sm">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div class="min-w-0">
                        <div class="fw-bold text-truncate">{{ $tag->name }}</div>
                        <div class="mt-1"><code class="bg-light p-1 rounded small">{{ $tag->slug }}</code></div>
                        <div class="mt-2">
                            <span class="badge bg-info"><i class="bi bi-newspaper"></i> {{ $tag->news_count ?? 0 }} uses</span>
                        </div>
                    </div>
                    <div class="btn-group btn-group-sm flex-shrink-0" role="group">
                        <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-outline-primary" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" style="display:inline;" onsubmit="return confirm('Delete this tag?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <p class="text-muted mb-0"><i class="bi bi-inbox"></i> No tags found. <a href="{{ route('admin.tags.create') }}">Create one now</a></p>
            </div>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if ($tags instanceof \Illuminate\Pagination\Paginator && $tags->hasPages())
    <div class="mt-3 d-flex justify-content-center">
        {{ $tags->links() }}
    </div>
@endif

@endsection
