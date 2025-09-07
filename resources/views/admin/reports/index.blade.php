@extends('layouts.app')

@section('title', 'Manage Reports - Admin')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="fas fa-flag me-2"></i>Manage Reports</h4>
                </div>
                <div class="card-body">
                    @if($reports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Reported Item</th>
                                        <th>Reporter</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                        <tr>
                                            <td>
                                                <span class="badge bg-{{ $report->type === 'inappropriate_content' ? 'danger' : ($report->type === 'spam' ? 'warning' : 'info') }}">
                                                    {{ ucwords(str_replace('_', ' ', $report->type)) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($report->reportable)
                                                    @if($report->reportable_type === 'App\Models\Book')
                                                        <div>
                                                            <strong>{{ $report->reportable->title }}</strong><br>
                                                            <small class="text-muted">by {{ $report->reportable->author }}</small>
                                                        </div>
                                                    @else
                                                        <div>
                                                            <strong>{{ $report->reportable->name }}</strong><br>
                                                            <small class="text-muted">User</small>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div>
                                                        <strong>Item Deleted</strong><br>
                                                        <small class="text-muted">The reported item no longer exists</small>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $report->reporter->name }}</strong><br>
                                                    <small class="text-muted">{{ $report->reporter->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @switch($report->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                        @break
                                                    @case('investigating')
                                                        <span class="badge bg-info">Investigating</span>
                                                        @break
                                                    @case('resolved')
                                                        <span class="badge bg-success">Resolved</span>
                                                        @break
                                                    @case('dismissed')
                                                        <span class="badge bg-secondary">Dismissed</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <small>{{ $report->created_at->format('M d, Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    @if($report->status === 'pending')
                                                        <form action="{{ route('admin.reports.update', $report) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="investigating">
                                                            <button type="submit" class="btn btn-sm btn-outline-info">
                                                                <i class="fas fa-search"></i> Investigate
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if($report->reportable && $report->reportable_type === 'App\Models\Book')
                                                        <form action="{{ route('admin.reports.delete-item', $report) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete the reported book? This cannot be undone.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-trash"></i> Delete Book
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reports->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-flag fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No reports found</h5>
                            <p class="text-muted">There are no reports to review at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
