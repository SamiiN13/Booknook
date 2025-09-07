@extends('layouts.app')

@section('title', 'Report Details - Admin')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="fas fa-flag me-2"></i>Report Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Report Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Type:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $report->type === 'inappropriate_content' ? 'danger' : ($report->type === 'spam' ? 'warning' : 'info') }}">
                                            {{ ucwords(str_replace('_', ' ', $report->type)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @switch($report->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @break
                                            @case('investigating')
                                                <span class="badge bg-info">Investigating</span>
                                                @break
                                            @case('resolved')
                                                <span class="badge bg-success">Resolved</span>
                                                @break
                                            @case('dismissed')
                                                <span class="badge bg-secondary">Dismissed</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Reported Date:</strong></td>
                                    <td>{{ $report->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @if($report->resolved_at)
                                    <tr>
                                        <td><strong>Resolved Date:</strong></td>
                                        <td>{{ $report->resolved_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Reporter Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $report->reporter->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $report->reporter->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Trust Score:</strong></td>
                                    <td>{{ $report->reporter->trust_score ?? 0 }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h6>Reported Item</h6>
                            @if($report->reportable_type === 'App\Models\Book')
                                <div class="card">
                                    <div class="card-body">
                                        <h5>{{ $report->reportable->title }}</h5>
                                        <p class="text-muted">by {{ $report->reportable->author }}</p>
                                        <p><strong>Genre:</strong> {{ $report->reportable->genre }}</p>
                                        <p><strong>Condition:</strong> {{ ucfirst(str_replace('_', ' ', $report->reportable->condition)) }}</p>
                                        @if($report->reportable->description)
                                            <p><strong>Description:</strong> {{ $report->reportable->description }}</p>
                                        @endif
                                        <a href="{{ route('books.show', $report->reportable) }}" class="btn btn-sm btn-outline-primary">View Book</a>
                                    </div>
                                </div>
                            @else
                                <div class="card">
                                    <div class="card-body">
                                        <h5>{{ $report->reportable->name }}</h5>
                                        <p class="text-muted">{{ $report->reportable->email }}</p>
                                        <p><strong>Trust Score:</strong> {{ $report->reportable->trust_score ?? 0 }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h6>Report Description</h6>
                            <div class="card">
                                <div class="card-body">
                                    <p>{{ $report->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($report->admin_notes)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6>Admin Notes</h6>
                                <div class="card">
                                    <div class="card-body">
                                        <p>{{ $report->admin_notes }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <h6>Actions</h6>
                            <div class="btn-group" role="group">
                                @if($report->status === 'pending')
                                    <form action="{{ route('admin.reports.update', $report) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="investigating">
                                        <button type="submit" class="btn btn-info">
                                            <i class="fas fa-search"></i> Mark as Investigating
                                        </button>
                                    </form>
                                @endif

                                @if($report->status === 'investigating')
                                    <form action="{{ route('admin.reports.update', $report) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="resolved">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check"></i> Mark as Resolved
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.reports.update', $report) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="dismissed">
                                        <button type="submit" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Dismiss Report
                                        </button>
                                    </form>
                                @endif

                                @if($report->reportable_type === 'App\Models\Book')
                                    <form action="{{ route('admin.reports.delete-item', $report) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete the reported book? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Delete Book
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
