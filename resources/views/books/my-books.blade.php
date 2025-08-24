@extends('layouts.app')

@section('title', 'My Books - Booknook')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-book me-2"></i>My Books</h2>
        <a href="{{ route('books.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Book
        </a>
    </div>

    @if($books->count() > 0)
        <div class="row">
            @foreach($books as $book)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 book-card">
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
                            <h6 class="text-muted">by {{ $book->author }}</h6>
                            
                            <div class="mb-2">
                                <span class="badge bg-{{ $book->status === 'available' ? 'success' : ($book->status === 'borrowed' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $book->status)) }}
                                </span>
                                <span class="badge bg-{{ $book->rarity === 'very_rare' ? 'danger' : ($book->rarity === 'rare' ? 'warning' : 'info') }}">
                                    {{ ucfirst(str_replace('_', ' ', $book->rarity)) }}
                                </span>
                            </div>
                            
                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    {{ $book->average_rating }}/5 ({{ $book->total_reviews }} reviews)
                                </small>
                            </p>
                            
                            <div class="mt-auto">
                                <a href="{{ route('books.show', $book) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $books->links() }}
        </div>
    @else
        <div class="text-center mt-5">
            <i class="fas fa-book fa-3x text-muted mb-3"></i>
            <h4>No books yet</h4>
            <p class="text-muted">You haven't shared any books yet. Start by adding your first book!</p>
            <a href="{{ route('books.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Share Your First Book
            </a>
        </div>
    @endif
</div>
@endsection
