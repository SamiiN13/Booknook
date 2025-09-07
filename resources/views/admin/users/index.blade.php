@extends('layouts.app')

@section('title', 'Manage Users - Admin Panel')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-users me-2"></i>Manage Users</h2>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                    <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>All Registered Users</h5>
        </div>
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Books Shared</th>
                                <th>Reviews Given</th>
                                <th>Trust Score</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->email_verified_at)
                                            <i class="fas fa-check-circle text-success ms-1" title="Email Verified"></i>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $user->books_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $user->reviews_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->trust_score >= 80 ? 'success' : ($user->trust_score >= 50 ? 'warning' : 'danger') }}">
                                            {{ $user->trust_score }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-info" onclick="viewUserDetails({{ $user->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="editUser({{ $user->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $users->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No users found</h5>
                    <p class="text-muted">No users have registered yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function viewUserDetails(userId) {
    // In a real application, this would open a modal or redirect to user details
    alert('View user details for ID: ' + userId);
}

function editUser(userId) {
    // In a real application, this would open an edit form
    alert('Edit user with ID: ' + userId);
}
</script>
@endsection
