@extends('layouts.app')

@section('title', 'My Requests - Booknook')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-hand-paper me-2"></i>My Book Requests</h4>
                </div>
                <div class="card-body">
                    @if($requests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Book</th>
                                        <th>Owner</th>
                                        <th>Status</th>
                                        <th>Requested</th>
                                        <th>Return Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-book text-primary"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <a href="{{ route('books.show', $request->book) }}" class="text-decoration-none">
                                                            <strong>{{ $request->book->title }}</strong><br>
                                                            <small class="text-muted">{{ $request->book->author }}</small>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $request->owner->name }}</strong><br>
                                                    <small class="text-muted">{{ $request->owner->badge }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @switch($request->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                        @break
                                                    @case('approved')
                                                        <span class="badge bg-success">Approved</span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge bg-info">Returned</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-secondary">Cancelled</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <small>{{ $request->requested_date->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                @if($request->return_date)
                                                    <small class="{{ $request->return_date < now() && $request->status === 'approved' ? 'text-danger' : '' }}">
                                                        {{ $request->return_date->format('M d, Y') }}
                                                        @if($request->return_date < now() && $request->status === 'approved')
                                                            <br><span class="text-danger">Overdue!</span>
                                                        @endif
                                                    </small>
                                                @else
                                                    <small class="text-muted">-</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($request->status === 'pending')
                                                    <form action="{{ route('book-requests.cancel', $request) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to cancel this request?')">
                                                            <i class="fas fa-times"></i> Cancel
                                                        </button>
                                                    </form>
                                                @elseif($request->status === 'approved')
                                                    <span class="text-success">
                                                        <i class="fas fa-check"></i> Approved
                                                    </span>
                                                @elseif($request->status === 'completed')
                                                    <span class="text-info">
                                                        <i class="fas fa-undo"></i> Returned
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $requests->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-hand-paper fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No book requests yet</h5>
                            <p class="text-muted">You haven't requested any books to borrow.</p>
                            <a href="{{ route('books.index') }}" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Browse Books
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
