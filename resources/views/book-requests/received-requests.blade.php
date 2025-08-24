@extends('layouts.app')

@section('title', 'Received Requests - Booknook')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-inbox me-2"></i>Received Book Requests</h4>
                </div>
                <div class="card-body">
                    @if($requests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Book</th>
                                        <th>Requester</th>
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
                                                    <strong>{{ $request->requester->name }}</strong><br>
                                                    <small class="text-muted">{{ $request->requester->badge }}</small>
                                                    <br>
                                                    <small class="text-muted">Trust Score: {{ $request->requester->trust_score }}</small>
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
                                                    <div class="btn-group" role="group">
                                                        <form action="{{ route('book-requests.approve', $request) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="fas fa-check"></i> Approve
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('book-requests.reject', $request) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to reject this request?')">
                                                                <i class="fas fa-times"></i> Reject
                                                            </button>
                                                        </form>
                                                    </div>
                                                @elseif($request->status === 'approved')
                                                    <form action="{{ route('book-requests.return', $request) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-info">
                                                            <i class="fas fa-undo"></i> Mark Returned
                                                        </button>
                                                    </form>
                                                @elseif($request->status === 'completed')
                                                    <span class="text-info">
                                                        <i class="fas fa-check-circle"></i> Returned
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if($request->message)
                                            <tr>
                                                <td colspan="6">
                                                    <div class="alert alert-light">
                                                        <strong>Message from {{ $request->requester->name }}:</strong><br>
                                                        {{ $request->message }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $requests->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No received requests</h5>
                            <p class="text-muted">You haven't received any requests for your books yet.</p>
                            <a href="{{ route('books.my-books') }}" class="btn btn-primary">
                                <i class="fas fa-book me-2"></i>View My Books
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
