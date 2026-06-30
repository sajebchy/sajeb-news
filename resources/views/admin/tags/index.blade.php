@extends('layouts.admin')

@section('page-title', 'Tags')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5><i class="bi bi-tags"></i> Tag Management</h5>
    <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> Add Tag
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search tags..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
            @if(request('search'))
                <div class="col-md-3">
                    <a href="{{ route('admin.tags.index') }}" class="btn btn-light w-100">
                        <i class="bi bi-x"></i> Clear
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="table-wrapper">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Uses</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tags as $tag)
                    <tr>
                        <td>
                            <strong>{{ $tag->name }}</strong>
                        </td>
                        <td><code class="bg-light p-2">{{ $tag->slug }}</code></td>
                        <td>
                            <span class="badge bg-info">{{ $tag->news_count ?? 0 }}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" style="display: inline;" onsubmit="return confirm('Delete this tag?');">
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
                            <p class="text-muted"><i class="bi bi-inbox"></i> No tags found. <a href="{{ route('admin.tags.create') }}">Create one now</a></p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($tags instanceof \Illuminate\Pagination\Paginator && $tags->hasPages())
        <div class="card-footer">
            {{ $tags->links() }}
        </div>
    @endif
</div>

@endsection
