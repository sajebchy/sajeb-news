@extends('layouts.admin')

@section('page-title', 'Newsletter Subscribers')

@section('content')
<div class="row mb-4 g-3">
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="stat-card primary">
            <div class="stat-card-icon text-primary">
                <i class="bi bi-envelope"></i>
            </div>
            <div>
                <div class="stat-card-value">{{ $totalSubscribers ?? 0 }}</div>
                <div class="stat-card-label">Total Subscribers</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-4">
        <div class="stat-card success">
            <div class="stat-card-icon text-success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div>
                <div class="stat-card-value">{{ $verifiedSubscribers ?? 0 }}</div>
                <div class="stat-card-label">Verified</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-4">
        <div class="stat-card warning">
            <div class="stat-card-icon text-warning">
                <i class="bi bi-exclamation-circle"></i>
            </div>
            <div>
                <div class="stat-card-value">{{ $unverifiedSubscribers ?? 0 }}</div>
                <div class="stat-card-label">Unverified</div>
            </div>
        </div>
    </div>
</div>

<!-- Newsletter Subscribers Table -->
<div class="row g-3">
    <div class="col-12">
        <div class="table-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5><i class="bi bi-envelope-open"></i> Newsletter Subscribers</h5>
            </div>

            @if ($subscribers && $subscribers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Subscribed Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscribers as $subscriber)
                                <tr>
                                    <td>
                                        <strong>{{ $subscriber->email }}</strong>
                                    </td>
                                    <td>
                                        @if ($subscriber->is_verified)
                                            <span class="badge bg-success">Verified</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $subscriber->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <form action="{{ route('admin.newsletters.destroy', $subscriber) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $subscribers->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted">No subscribers yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
