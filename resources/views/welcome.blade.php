@extends('layouts.app')

@section('title', 'Welcome to Booknook')

@section('content')
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Share Your Books with the World</h1>
                <p class="lead mb-4">Join our community of book lovers. Share your favorite books, discover new ones, and connect with fellow readers.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('books.index') }}" class="btn btn-light btn-lg">Browse Books</a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">Join Now</a>
                    @endguest
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-books fa-10x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-md-4 text-center mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-share-alt fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Share Books</h5>
                    <p class="card-text">Add your books to the community library and let others discover your collection.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-center mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-search fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Discover</h5>
                    <p class="card-text">Browse through thousands of books shared by our community members.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-center mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Connect</h5>
                    <p class="card-text">Build your reading community and connect with fellow book enthusiasts.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="mb-4">Why Choose Booknook?</h2>
                <ul class="list-unstyled">
                    <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Free to join and share</li>
                    <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Trusted community of readers</li>
                    <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Easy book management</li>
                    <li class="mb-3"><i class="fas fa-check text-success me-2"></i>Secure borrowing system</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow">
                    <div class="card-body p-4">
                        <h5 class="card-title">Get Started Today</h5>
                        <p class="card-text">Join thousands of readers who are already sharing their books.</p>
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-primary">Create Account</a>
                        @else
                            <a href="{{ route('books.create') }}" class="btn btn-primary">Share a Book</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
