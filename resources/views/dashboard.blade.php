@extends('layouts.app')

@section('title', 'Dashboard - Booknook')

@section('content')
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-home me-2"></i>Welcome back, {{ Auth::user()->name }}!</h2>
            <p class="text-muted">Manage your books and discover new ones</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-plus-circle fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Share a Book</h5>
                    <p class="card-text">Add your books to the community library</p>
                    <a href="{{ route('books.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Share Book
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-books fa-3x text-success mb-3"></i>
                    <h5 class="card-title">My Books</h5>
                    <p class="card-text">View and manage your shared books</p>
                    <a href="{{ route('books.my-books') }}" class="btn btn-success">
                        <i class="fas fa-eye me-2"></i>View My Books
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-search fa-3x text-info mb-3"></i>
                    <h5 class="card-title">Browse Books</h5>
                    <p class="card-text">Discover books shared by others</p>
                    <a href="{{ route('books.index') }}" class="btn btn-info">
                        <i class="fas fa-search me-2"></i>Browse Books
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Welcome to Booknook!</h6>
                                <small class="text-muted">Just now</small>
                            </div>
                            <p class="mb-1">You've successfully logged into your account.</p>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Ready to share?</h6>
                                <small class="text-muted">Get started</small>
                            </div>
                            <p class="mb-1">Start sharing your books with the community.</p>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Discover books</h6>
                                <small class="text-muted">Browse library</small>
                            </div>
                            <p class="mb-1">Find interesting books shared by other members.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Getting Started</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0">
                            <h6 class="mb-1"><i class="fas fa-check-circle text-success me-2"></i>Account Created</h6>
                            <small class="text-muted">Your account is ready to use</small>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <h6 class="mb-1"><i class="fas fa-book text-primary me-2"></i>Share Books</h6>
                            <small class="text-muted">Add your first book to the library</small>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <h6 class="mb-1"><i class="fas fa-users text-info me-2"></i>Connect</h6>
                            <small class="text-muted">Join the community of readers</small>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <h6 class="mb-1"><i class="fas fa-star text-warning me-2"></i>Build Trust</h6>
                            <small class="text-muted">Earn trust score through good behavior</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 