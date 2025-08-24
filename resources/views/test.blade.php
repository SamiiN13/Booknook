@extends('layouts.app')

@section('title', 'Test Page - Booknook')

@section('content')
<div class="container mt-4">
    <h2>Test Page - All Routes</h2>
    
    <div class="row">
        <div class="col-md-6">
            <h4>Book Routes:</h4>
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="{{ route('books.index') }}">Browse Books</a>
                </li>
                <li class="list-group-item">
                    <a href="{{ route('books.create') }}">Share a Book</a>
                </li>
                <li class="list-group-item">
                    <a href="{{ route('books.my-books') }}">My Books</a>
                </li>
            </ul>
        </div>
        
        <div class="col-md-6">
            <h4>Request Routes:</h4>
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="{{ route('book-requests.my-requests') }}">My Requests</a>
                </li>
                <li class="list-group-item">
                    <a href="{{ route('book-requests.received') }}">Received Requests</a>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="mt-4">
        <h4>Current User:</h4>
        @auth
            <p>Logged in as: {{ Auth::user()->name }} ({{ Auth::user()->email }})</p>
        @else
            <p>Not logged in</p>
        @endauth
    </div>
</div>
@endsection
