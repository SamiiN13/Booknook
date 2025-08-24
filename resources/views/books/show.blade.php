@extends('layouts.app')

@section('title', $book->title . ' - Booknook')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($book->image_path)
                                <img src="{{ asset('storage/' . $book->image_path) }}" class="img-fluid rounded" alt="{{ $book->title }}">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <i class="fas fa-book fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h2 class="card-title">{{ $book->title }}</h2>
                            <p class="text-muted">by {{ $book->author }}</p>
                            
                            <div class="mb-3">
                                <span class="badge bg-primary me-2">{{ ucfirst($book->genre) }}</span>
                                <span class="badge bg-{{ $book->rarity === 'very_rare' ? 'danger' : ($book->rarity === 'rare' ? 'warning' : 'success') }}">{{ ucfirst(str_replace('_', ' ', $book->rarity)) }}</span>
                                @if($book->min_trust_score > 0)
                                    <span class="badge bg-info">Min Trust: {{ $book->min_trust_score }}</span>
                                @endif
                            </div>

                            <div class="mb-3">
                                <strong>Condition:</strong> {{ ucfirst(str_replace('_', ' ', $book->condition)) }}<br>
                                @if($book->published_year)
                                    <strong>Published:</strong> {{ $book->published_year }}<br>
                                @endif
                                @if($book->pages)
                                    <strong>Pages:</strong> {{ $book->pages }}<br>
                                @endif
                                @if($book->language)
                                    <strong>Language:</strong> {{ $book->language }}<br>
                                @endif
                                @if($book->isbn)
                                    <strong>ISBN:</strong> {{ $book->isbn }}<br>
                                @endif
                                <strong>Max Loan Duration:</strong> {{ $book->max_loan_duration }} days
                            </div>

                            @if($book->description)
                                <div class="mb-3">
                                    <h5>Description</h5>
                                    <p>{{ $book->description }}</p>
                                </div>
                            @endif

                            <div class="mb-3">
                                <strong>Shared by:</strong> 
                                <a href="#" class="text-decoration-none">{{ $book->user->name }}</a>
                                <span class="badge bg-secondary ms-2">{{ $book->user->badge }}</span>
                                <span class="text-muted">(Trust Score: {{ $book->user->trust_score }})</span>
                            </div>

                            @auth
                                @if(Auth::id() !== $book->user_id && $book->status === 'available')
                                    @if(Auth::user()->trust_score >= $book->min_trust_score)
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestModal">
                                            <i class="fas fa-hand-paper me-2"></i>Request to Borrow
                                        </button>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-lock me-2"></i>
                                            This book requires a trust score of {{ $book->min_trust_score }}. Your current score: {{ Auth::user()->trust_score }}
                                        </div>
                                    @endif
                                @elseif(Auth::id() === $book->user_id)
                                    <span class="badge bg-success">Your Book</span>
                                @else
                                    <span class="badge bg-secondary">Not Available</span>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary">Login to Request</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="card shadow mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-star me-2"></i>Reviews
                        <span class="badge bg-primary ms-2">{{ $book->reviews->count() }}</span>
                        @if($book->reviews->count() > 0)
                            <span class="text-muted ms-2">({{ number_format($book->average_rating, 1) }}/5.0)</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @auth
                        @if(!$book->reviews->where('user_id', Auth::id())->first())
                            <button class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                <i class="fas fa-plus me-2"></i>Write a Review
                            </button>
                        @endif
                    @endauth

                    @if($book->reviews->count() > 0)
                        @foreach($book->reviews as $review)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ $review->user->name }}</strong>
                                        <span class="badge bg-secondary ms-2">{{ $review->user->badge }}</span>
                                        @if($review->is_verified)
                                            <span class="badge bg-success ms-1"><i class="fas fa-check"></i> Verified</span>
                                        @endif
                                    </div>
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->review)
                                    <p class="mt-2 mb-1">{{ $review->review }}</p>
                                @endif
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No reviews yet. Be the first to review this book!</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Report Book -->
            @auth
                @if(Auth::id() !== $book->user_id)
                    <div class="card shadow mb-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-flag me-2"></i>Report Issue</h6>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#reportModal">
                                Report this book
                            </button>
                        </div>
                    </div>
                @endif
            @endauth

            <!-- Similar Books -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-book me-2"></i>Similar Books</h6>
                </div>
                <div class="card-body">
                    @php
                        $similarBooks = \App\Models\Book::where('genre', $book->genre)
                            ->where('id', '!=', $book->id)
                            ->where('status', 'available')
                            ->limit(3)
                            ->get();
                    @endphp
                    
                    @if($similarBooks->count() > 0)
                        @foreach($similarBooks as $similarBook)
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-book text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <a href="{{ route('books.show', $similarBook) }}" class="text-decoration-none">
                                        <strong>{{ $similarBook->title }}</strong><br>
                                        <small class="text-muted">{{ $similarBook->author }}</small>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted small">No similar books found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Request Modal -->
@auth
    @if(Auth::id() !== $book->user_id && $book->status === 'available')
        <div class="modal fade" id="requestModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Request to Borrow</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('books.request', $book) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p>You're requesting to borrow <strong>{{ $book->title }}</strong> by {{ $book->author }}.</p>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message to the owner (optional)</label>
                                <textarea class="form-control" id="message" name="message" rows="3" placeholder="Tell the owner why you'd like to borrow this book..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Send Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Write a Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('reviews.store', $book) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <div class="rating">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                                    <label for="star{{ $i }}"><i class="fas fa-star"></i></label>
                                @endfor
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="review" class="form-label">Review (optional)</label>
                            <textarea class="form-control" id="review" name="review" rows="4" placeholder="Share your thoughts about this book..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Report Modal -->
    @if(Auth::id() !== $book->user_id)
        <div class="modal fade" id="reportModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Report Book</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('reports.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="reportable_type" value="App\Models\Book">
                        <input type="hidden" name="reportable_id" value="{{ $book->id }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="type" class="form-label">Report Type</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Select a reason</option>
                                    <option value="inappropriate_content">Inappropriate Content</option>
                                    <option value="spam">Spam</option>
                                    <option value="fake_book">Fake Book</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Please describe the issue..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Submit Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endauth

<style>
.rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating input {
    display: none;
}

.rating label {
    cursor: pointer;
    font-size: 1.5em;
    color: #ddd;
    margin: 0 2px;
}

.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
    color: #ffd700;
}
</style>
@endsection
