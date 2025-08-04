@extends('layouts.app')

@section('title', 'Review Books - Admin Dashboard')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-tasks me-2"></i>Review Pending Books</h2>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    @if($pendingBooks->count() > 0)
        <div class="row">
            @foreach($pendingBooks as $book)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow">
                        @if($book->image_path)
                            <img src="{{ Storage::url($book->image_path) }}" class="card-img-top" 
                                 alt="{{ $book->title }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="fas fa-book fa-3x text-muted"></i>
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <h5 class="card-title">{{ $book->title }}</h5>
                            <p class="card-text text-muted">by {{ $book->author }}</p>
                            
                            @if($book->genre)
                                <span class="badge bg-secondary mb-2">{{ $book->genre }}</span>
                            @endif
                            
                            <span class="badge bg-{{ $book->condition == 'new' ? 'success' : ($book->condition == 'like_new' ? 'info' : ($book->condition == 'good' ? 'primary' : ($book->condition == 'fair' ? 'warning' : 'danger'))) }}">
                                {{ ucfirst(str_replace('_', ' ', $book->condition)) }}
                            </span>
                            
                            @if($book->description)
                                <p class="card-text mt-3">{{ Str::limit($book->description, 100) }}</p>
                            @endif
                            
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>Shared by: {{ $book->user->name }}
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>Added: {{ $book->created_at->format('M d, Y') }}
                                </small>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent">
                            <div class="row">
                                <div class="col-6">
                                    <form method="POST" action="{{ route('admin.books.approve', $book) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm w-100" 
                                                onclick="return confirm('Approve this book?')">
                                            <i class="fas fa-check me-1"></i>Approve
                                        </button>
                                    </form>
                                </div>
                                <div class="col-6">
                                    <form method="POST" action="{{ route('admin.books.reject', $book) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100" 
                                                onclick="return confirm('Reject this book? This action cannot be undone.')">
                                            <i class="fas fa-times me-1"></i>Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row">
            <div class="col-12">
                <nav aria-label="Pending books pagination">
                    {{ $pendingBooks->links() }}
                </nav>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5>No pending books!</h5>
                        <p class="text-muted">All books have been reviewed. Great job!</p>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 