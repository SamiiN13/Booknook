@extends('layouts.app')

@section('title', 'Edit Book - Booknook')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Book</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('books.update', $book) }}" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Book Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $book->title) }}" required>
                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="author" class="form-label">Author *</label>
                                    <input type="text" class="form-control @error('author') is-invalid @enderror" id="author" name="author" value="{{ old('author', $book->author) }}" required>
                                    @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="isbn" class="form-label">ISBN</label>
                                            <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn" value="{{ old('isbn', $book->isbn) }}">
                                            @error('isbn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="genre" class="form-label">Genre *</label>
                                            <select class="form-select @error('genre') is-invalid @enderror" id="genre" name="genre" required autocomplete="off">
                                                <option value="">Select Genre</option>
                                                @foreach(\App\Models\Book::getAvailableGenres() as $genre)
                                                    <option value="{{ strtolower(str_replace(' ', '-', $genre)) }}" {{ old('genre', $book->genre) == strtolower(str_replace(' ', '-', $genre)) ? 'selected' : '' }}>{{ $genre }}</option>
                                                @endforeach
                                            </select>
                                            @error('genre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="published_year" class="form-label">Published Year</label>
                                            <input type="number" class="form-control @error('published_year') is-invalid @enderror" id="published_year" name="published_year" value="{{ old('published_year', $book->published_year) }}" min="1800" max="{{ date('Y') + 1 }}">
                                            @error('published_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="pages" class="form-label">Number of Pages</label>
                                            <input type="number" class="form-control @error('pages') is-invalid @enderror" id="pages" name="pages" value="{{ old('pages', $book->pages) }}" min="1">
                                            @error('pages')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="condition" class="form-label">Book Condition *</label>
                                            <select class="form-select @error('condition') is-invalid @enderror" id="condition" name="condition" required>
                                                <option value="">Select Condition</option>
                                                <option value="new" {{ old('condition', $book->condition) == 'new' ? 'selected' : '' }}>New</option>
                                                <option value="like_new" {{ old('condition', $book->condition) == 'like_new' ? 'selected' : '' }}>Like New</option>
                                                <option value="good" {{ old('condition', $book->condition) == 'good' ? 'selected' : '' }}>Good</option>
                                                <option value="fair" {{ old('condition', $book->condition) == 'fair' ? 'selected' : '' }}>Fair</option>
                                                <option value="poor" {{ old('condition', $book->condition) == 'poor' ? 'selected' : '' }}>Poor</option>
                                            </select>
                                            @error('condition')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="language" class="form-label">Language</label>
                                            <input type="text" class="form-control @error('language') is-invalid @enderror" id="language" name="language" value="{{ old('language', $book->language) }}">
                                            @error('language')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="rarity" class="form-label">Rarity *</label>
                                            <select class="form-select @error('rarity') is-invalid @enderror" id="rarity" name="rarity" required>
                                                <option value="">Select rarity...</option>
                                                <option value="common" {{ old('rarity', $book->rarity) == 'common' ? 'selected' : '' }}>Common</option>
                                                <option value="uncommon" {{ old('rarity', $book->rarity) == 'uncommon' ? 'selected' : '' }}>Uncommon</option>
                                                <option value="rare" {{ old('rarity', $book->rarity) == 'rare' ? 'selected' : '' }}>Rare</option>
                                                <option value="very_rare" {{ old('rarity', $book->rarity) == 'very_rare' ? 'selected' : '' }}>Very Rare</option>
                                            </select>
                                            @error('rarity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="max_loan_duration" class="form-label">Max Loan Duration (Days) *</label>
                                            <input type="number" class="form-control @error('max_loan_duration') is-invalid @enderror" id="max_loan_duration" name="max_loan_duration" value="{{ old('max_loan_duration', $book->max_loan_duration) }}" min="1" max="90" required>
                                            @error('max_loan_duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            <small class="form-text text-muted">Maximum number of days someone can borrow this book</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $book->description) }}</textarea>
                                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Book Cover Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    @if($book->image_path)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url($book->image_path) }}" alt="{{ $book->title }}" class="img-fluid rounded">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('books.my-books') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
