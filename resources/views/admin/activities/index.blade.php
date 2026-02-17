@extends('layouts.admin')

@section('page-title', 'Activity Logs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5><i class="bi bi-clock-history"></i> Activity Logs - Admin Actions & Changes</h5>
</div>

<!-- Filters Card -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search user or action..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="action" class="form-select">
                    <option value="">All Actions</option>
                    <option value="created" @selected(request('action') === 'created')>Created</option>
                    <option value="updated" @selected(request('action') === 'updated')>Updated</option>
                    <option value="deleted" @selected(request('action') === 'deleted')>Deleted</option>
                    <option value="viewed" @selected(request('action') === 'viewed')>Viewed</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="model_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="News" @selected(request('model_type') === 'News')>News</option>
                    <option value="Category" @selected(request('model_type') === 'Category')>Category</option>
                    <option value="Tag" @selected(request('model_type') === 'Tag')>Tag</option>
                    <option value="User" @selected(request('model_type') === 'User')>User</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Activity Timeline -->
<div class="card shadow-sm">
    <div class="table-wrapper">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light sticky-top">
                    <tr>
                        <th style="width: 15%;">User</th>
                        <th style="width: 12%;">Action</th>
                        <th style="width: 12%;">Type</th>
                        <th style="width: 40%;">Details</th>
                        <th style="width: 21%;">Time & IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $activity)
                        <tr class="border-bottom">
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    @if($activity->user && $activity->user->avatar)
                                        <img src="{{ asset('storage/' . $activity->user->avatar) }}" alt="{{ $activity->user->name }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                    @else
                                        <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;">
                                            {{ $activity->user ? strtoupper(substr($activity->user->name, 0, 1)) : 'S' }}
                                        </div>
                                    @endif
                                    <div>
                                        <strong style="display: block;">{{ $activity->user->name ?? 'System' }}</strong>
                                        <small class="text-muted" style="display: block; font-size: 11px;">{{ $activity->user->email ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $actionColors = [
                                        'created' => 'success',
                                        'updated' => 'info',
                                        'deleted' => 'danger',
                                        'viewed' => 'secondary'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $actionColors[$activity->action ?? 'viewed'] ?? 'secondary' }} py-2 px-2" style="font-size: 11px;">
                                    @if($activity->action === 'created')
                                        <i class="bi bi-plus-circle"></i> Created
                                    @elseif($activity->action === 'updated')
                                        <i class="bi bi-pencil-square"></i> Updated
                                    @elseif($activity->action === 'deleted')
                                        <i class="bi bi-trash"></i> Deleted
                                    @else
                                        <i class="bi bi-eye"></i> Viewed
                                    @endif
                                </span>
                            </td>
                            <td>
                                @php
                                    $typeIcon = [
                                        'News' => 'bi-file-text',
                                        'Category' => 'bi-folder',
                                        'Tag' => 'bi-tags',
                                        'User' => 'bi-people'
                                    ];
                                @endphp
                                <span class="badge bg-light text-dark py-2 px-2" style="font-size: 11px;">
                                    <i class="bi {{ $typeIcon[$activity->model_type] ?? 'bi-box' }}"></i>
                                    {{ $activity->model_type ?? 'General' }}
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 13px;">
                                    <strong>{{ $activity->action == 'created' ? 'Created new ' : ($activity->action == 'deleted' ? 'Deleted ' : 'Modified ') }}{{ strtolower($activity->model_type) }}</strong>
                                    
                                    @if($activity->changes)
                                        <div style="margin-top: 6px; padding-top: 8px; border-top: 1px solid #e9ecef;">
                                            @php
                                                $changes = json_decode($activity->changes, true);
                                            @endphp
                                            
                                            @if(is_array($changes))
                                                @foreach($changes as $field => $value)
                                                    <small class="d-block text-muted" style="margin-top: 4px;">
                                                        <i class="bi bi-arrow-right-short"></i>
                                                        <strong>{{ ucwords(str_replace('_', ' ', $field)) }}:</strong>
                                                        @if(is_array($value))
                                                            <br><span style="color: #dc3545;">Before:</span> <code style="background: #f5f5f5; padding: 2px 4px; border-radius: 3px; font-size: 11px;">{{ $value['before'] ?? '-' }}</code><br>
                                                            <span style="color: #28a745;">After:</span> <code style="background: #f5f5f5; padding: 2px 4px; border-radius: 3px; font-size: 11px;">{{ $value['after'] ?? '-' }}</code>
                                                        @else
                                                            <code style="background: #f5f5f5; padding: 2px 4px; border-radius: 3px; font-size: 11px;">{{ $value }}</code>
                                                        @endif
                                                    </small>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 12px;">
                                    <div class="mb-2">
                                        <small><i class="bi bi-calendar-event"></i> {{ $activity->created_at?->format('M d, Y') ?? '-' }}</small><br>
                                        <small><i class="bi bi-clock"></i> {{ $activity->created_at?->format('H:i:s') ?? '-' }}</small>
                                    </div>
                                    <div class="border-top pt-2">
                                        <small class="text-muted">
                                            <i class="bi bi-globe"></i> {{ $activity->ip_address ?? 'Unknown' }}<br>
                                            <small class="text-muted" style="display: block; font-size: 10px; word-break: break-word;">
                                                @if($activity->user_agent)
                                                    {{ substr($activity->user_agent, 0, 40) }}...
                                                @else
                                                    Unknown
                                                @endif
                                            </small>
                                        </small>
                                    </div>
                                    <div class="mt-1">
                                        <small class="text-primary" style="cursor: pointer;" data-bs-toggle="tooltip" title="{{ $activity->created_at?->diffForHumans() }}">
                                            {{ $activity->created_at?->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 48px; color: #ddd;"></i>
                                <p class="text-muted mt-3">No activity logs found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($activities instanceof \Illuminate\Pagination\Paginator && $activities->hasPages())
            <div class="card-footer">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }

    .table-wrapper {
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }

    code {
        font-family: 'Courier New', monospace;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    @media (max-width: 768px) {
        .table th {
            font-size: 12px;
        }

        .table td {
            font-size: 12px;
            padding: 8px !important;
        }

        .badge {
            font-size: 10px !important;
        }
    }
</style>

@endsection
