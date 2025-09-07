@extends('layouts.app')

@section('title', 'Browse Books - Booknook')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-books me-2"></i>Browse Available Books</h2>
            <p class="text-muted">Discover books shared by our community members</p>
        </div>
        <div class="col-md-4 text-end">
            @auth
                <a href="{{ route('books.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Share a Book
                </a>
            @endauth
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('books.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Search by title or author..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="genre">
                                <option value="">All Genres</option>
                                @foreach(($genres ?? []) as $genre)
                                    <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>{{ $genre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="condition">
                                <option value="">All Conditions</option>
                                <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>New</option>
                                <option value="like_new" {{ request('condition') == 'like_new' ? 'selected' : '' }}>Like New</option>
                                <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Good</option>
                                <option value="fair" {{ request('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                                <option value="poor" {{ request('condition') == 'poor' ? 'selected' : '' }}>Poor</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Books Grid -->
    @if($books->count() > 0)
        <div class="row">
            @foreach($books as $book)
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card book-card h-100 shadow-sm">
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
                            <h6 class="card-title">{{ $book->title }}</h6>
                            <p class="card-text text-muted mb-2">by {{ $book->author }}</p>
                            
                            @if($book->genre)
                                <span class="badge bg-secondary mb-2">{{ $book->genre }}</span>
                            @endif
                            
                            <span class="badge bg-{{ $book->condition == 'new' ? 'success' : ($book->condition == 'like_new' ? 'info' : ($book->condition == 'good' ? 'primary' : ($book->condition == 'fair' ? 'warning' : 'danger'))) }}">
                                {{ ucfirst(str_replace('_', ' ', $book->condition)) }}
                            </span>
                            
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>{{ $book->user->name }}
                                </small>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('books.show', $book) }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-eye me-1"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row">
            <div class="col-12">
                <nav aria-label="Books pagination">
                    {{ $books->links() }}
                </nav>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-books fa-3x text-muted mb-3"></i>
                        <h5>No books found</h5>
                        <p class="text-muted">No books match your search criteria. Try adjusting your filters.</p>
                        @auth
                            <a href="{{ route('books.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Be the first to share a book!
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 