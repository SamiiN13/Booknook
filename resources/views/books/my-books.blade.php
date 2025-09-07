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
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">{{ $book->title }}</h5>
                                <small class="text-muted">Status: {{ ucfirst(str_replace('_',' ', $book->status)) }}</small>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" onsubmit="return confirm('Delete this book?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
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
