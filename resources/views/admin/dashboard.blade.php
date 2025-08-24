@extends('layouts.app')

@section('title', 'Admin Dashboard - Booknook')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</h2>
                <div>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-danger me-2">
                        <i class="fas fa-flag me-2"></i>Manage Reports
                    </a>
                    <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['total_users'] }}</h4>
                            <p class="mb-0">Total Users</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['total_books'] }}</h4>
                            <p class="mb-0">Total Books</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['pending_books'] }}</h4>
                            <p class="mb-0">Pending Books</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['pending_reports'] }}</h4>
                            <p class="mb-0">Pending Reports</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-flag fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Pending Books -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Pending Book Approvals</h5>
                </div>
                <div class="card-body">
                    @if($pendingBooks->count() > 0)
                        @foreach($pendingBooks as $book)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $book->title }}</h6>
                                        <p class="text-muted mb-1">by {{ $book->author }}</p>
                                        <small class="text-muted">Shared by {{ $book->user->name }} ({{ $book->user->badge }})</small>
                                        <br>
                                        <small class="text-muted">Added {{ $book->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <form action="{{ route('admin.books.approve', $book) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.books.reject', $book) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to reject this book?')">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center">
                            <a href="{{ route('admin.books.index') }}" class="btn btn-outline-warning">
                                View All Pending Books
                            </a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <p class="text-muted mb-0">No pending books to approve!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pending Reports -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-flag me-2"></i>Recent Reports</h5>
                </div>
                <div class="card-body">
                    @if($pendingReports->count() > 0)
                        @foreach($pendingReports as $report)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="badge bg-{{ $report->type === 'inappropriate_content' ? 'danger' : ($report->type === 'spam' ? 'warning' : 'info') }} me-2">
                                                {{ ucwords(str_replace('_', ' ', $report->type)) }}
                                            </span>
                                            <span class="badge bg-warning">{{ ucfirst($report->status) }}</span>
                                        </div>
                                        <h6 class="mb-1">
                                            @if($report->reportable_type === 'App\Models\Book')
                                                {{ $report->reportable->title }}
                                            @else
                                                {{ $report->reportable->name }}
                                            @endif
                                        </h6>
                                        <small class="text-muted">Reported by {{ $report->reporter->name }}</small>
                                        <br>
                                        <small class="text-muted">{{ $report->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center">
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-danger">
                                View All Reports
                            </a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-flag fa-2x text-success mb-2"></i>
                            <p class="text-muted mb-0">No pending reports!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('admin.books.index') }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-book me-2"></i>Review Books
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-danger w-100 mb-2">
                                <i class="fas fa-flag me-2"></i>Manage Reports
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('books.index') }}" class="btn btn-outline-success w-100 mb-2">
                                <i class="fas fa-search me-2"></i>Browse Books
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-warning w-100 mb-2" onclick="sendReturnReminders()">
                                <i class="fas fa-bell me-2"></i>Send Reminders
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function sendReturnReminders() {
    if (confirm('Send return reminders to users with overdue books?')) {
        // In a real application, this would make an AJAX call to trigger the command
        alert('Return reminders sent!');
    }
}
</script>
@endsection 