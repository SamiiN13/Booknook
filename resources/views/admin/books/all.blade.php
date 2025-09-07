@extends('layouts.app')

@section('title', 'All Books - Admin Panel')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-book me-2"></i>All Books</h2>
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
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>All Books in System</h5>
        </div>
        <div class="card-body">
            @if($books->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Owner</th>
                                <th>Genre</th>
                                <th>Status</th>
                                <th>Reviews</th>
                                <th>Added</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($books as $book)
                                <tr>
                                    <td>{{ $book->id }}</td>
                                    <td>
                                        <strong>{{ $book->title }}</strong>
                                        @if($book->image_path)
                                            <i class="fas fa-image text-info ms-1" title="Has Cover Image"></i>
                                        @endif
                                    </td>
                                    <td>{{ $book->author }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $book->user->name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($book->genre) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $book->status === 'available' ? 'success' : ($book->status === 'pending_approval' ? 'warning' : 'danger') }}">
                                            {{ ucfirst(str_replace('_', ' ', $book->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $book->reviews_count }}</span>
                                    </td>
                                    <td>{{ $book->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($book->status === 'pending_approval')
                                                <form action="{{ route('admin.books.approve', $book) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.books.reject', $book) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to reject this book?')">
                                                        <i class="fas fa-times"></i>
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

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $books->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No books found</h5>
                    <p class="text-muted">No books have been shared yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
