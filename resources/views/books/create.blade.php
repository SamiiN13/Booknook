@extends('layouts.app')

@section('title', 'Share a Book - Booknook')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-plus me-2"></i>Share a Book</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Book Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="author" class="form-label">Author *</label>
                                    <input type="text" class="form-control @error('author') is-invalid @enderror" 
                                           id="author" name="author" value="{{ old('author') }}" required>
                                    @error('author')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="isbn" class="form-label">ISBN</label>
                                            <input type="text" class="form-control @error('isbn') is-invalid @enderror" 
                                                   id="isbn" name="isbn" value="{{ old('isbn') }}">
                                            @error('isbn')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="genre" class="form-label">Genre *</label>
                                            <select class="form-select @error('genre') is-invalid @enderror" 
                                                    id="genre" name="genre" required autocomplete="off">
                                                <option value="">Select Genre</option>
                                                @foreach(\App\Models\Book::getAvailableGenres() as $genre)
                                                    <option value="{{ strtolower(str_replace(' ', '-', $genre)) }}" 
                                                            {{ old('genre') == strtolower(str_replace(' ', '-', $genre)) ? 'selected' : '' }}>
                                                        {{ $genre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('genre')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="published_year" class="form-label">Published Year</label>
                                            <input type="number" class="form-control @error('published_year') is-invalid @enderror" 
                                                   id="published_year" name="published_year" value="{{ old('published_year') }}" 
                                                   min="1800" max="{{ date('Y') + 1 }}">
                                            @error('published_year')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="pages" class="form-label">Number of Pages</label>
                                            <input type="number" class="form-control @error('pages') is-invalid @enderror" 
                                                   id="pages" name="pages" value="{{ old('pages') }}" min="1">
                                            @error('pages')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="condition" class="form-label">Book Condition *</label>
                                            <select class="form-select @error('condition') is-invalid @enderror" 
                                                    id="condition" name="condition" required>
                                                <option value="">Select Condition</option>
                                                <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>New</option>
                                                <option value="like_new" {{ old('condition') == 'like_new' ? 'selected' : '' }}>Like New</option>
                                                <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Good</option>
                                                <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>Fair</option>
                                                <option value="poor" {{ old('condition') == 'poor' ? 'selected' : '' }}>Poor</option>
                                            </select>
                                            @error('condition')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                                                    <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="language" class="form-label">Language</label>
                                        <input type="text" class="form-control @error('language') is-invalid @enderror" 
                                               id="language" name="language" value="{{ old('language', 'English') }}">
                                        @error('language')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                                                            <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="rarity" class="form-label">Rarity *</label>
                                            <select class="form-select @error('rarity') is-invalid @enderror" id="rarity" name="rarity" required>
                                                <option value="">Select rarity...</option>
                                                <option value="common" {{ old('rarity') == 'common' ? 'selected' : '' }}>Common</option>
                                                <option value="uncommon" {{ old('rarity') == 'uncommon' ? 'selected' : '' }}>Uncommon</option>
                                                <option value="rare" {{ old('rarity') == 'rare' ? 'selected' : '' }}>Rare</option>
                                                <option value="very_rare" {{ old('rarity') == 'very_rare' ? 'selected' : '' }}>Very Rare</option>
                                            </select>
                                            @error('rarity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="max_loan_duration" class="form-label">Max Loan Duration (Days) *</label>
                                            <input type="number" class="form-control @error('max_loan_duration') is-invalid @enderror" 
                                                   id="max_loan_duration" name="max_loan_duration" value="{{ old('max_loan_duration', 14) }}" 
                                                   min="1" max="90" required>
                                            @error('max_loan_duration')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Maximum number of days someone can borrow this book</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" 
                                              placeholder="Tell us about this book...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Book Cover Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Upload a cover image (JPEG, PNG, GIF, max 2MB)
                                    </small>
                                </div>

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Tips for sharing:</h6>
                                        <ul class="list-unstyled small">
                                            <li><i class="fas fa-check text-success me-2"></i>Be honest about condition</li>
                                            <li><i class="fas fa-check text-success me-2"></i>Include clear photos</li>
                                            <li><i class="fas fa-check text-success me-2"></i>Provide accurate descriptions</li>
                                            <li><i class="fas fa-check text-success me-2"></i>Books will be reviewed by admin</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-share me-2"></i>Share Book
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 