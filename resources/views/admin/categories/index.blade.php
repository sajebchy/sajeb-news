@extends('layouts.admin')

@section('page-title', 'Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5><i class="bi bi-folder"></i> Category Management</h5>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> Add Category
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search categories..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
            @if(request('search'))
                <div class="col-md-3">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-light w-100">
                        <i class="bi bi-x"></i> Clear
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="table-wrapper mt-3">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>featured Order</th>
                    <th>Description</th>
                    <th>Posts</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>
                            <strong>{{ $category->name }}</strong>
                        </td>
                        <td><code class="bg-light p-2">{{ $category->slug }}</code></td>
                        <td>
                            @if($category->featured_order)
                                <span class="badge bg-success">
                                    <i class="bi bi-star-fill"></i> #{{ $category->featured_order }}
                                </span>
                            @else
                                <span class="badge bg-secondary">Not Featured</span>
                            @endif
                        </td>
                        <td>{{ \Str::limit($category->description ?? '', 50) }}</td>
                        <td>
                            <span class="badge bg-info">{{ $category->news_count ?? 0 }}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" style="display: inline;" onsubmit="return confirm('Delete this category?');">
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
                    <td colspan="6" class="text-center py-5">
                        <p class="text-muted">No categories found.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection
