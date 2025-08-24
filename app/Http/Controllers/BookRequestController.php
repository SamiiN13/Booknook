<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookRequestController extends Controller
{
    public function store(Request $request, Book $book)
    {
        // Check if user already has a pending request for this book
        $existingRequest = BookRequest::where('book_id', $book->id)
            ->where('requester_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'You already have a request for this book.');
        }

        // Check if user is trying to request their own book
        if ($book->user_id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot request your own book.');
        }

        BookRequest::create([
            'book_id' => $book->id,
            'requester_id' => Auth::id(),
            'owner_id' => $book->user_id,
            'status' => 'pending',
            'message' => $request->message,
            'requested_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Book request sent successfully!');
    }

    public function myRequests()
    {
        $requests = BookRequest::where('requester_id', Auth::id())
            ->with(['book', 'owner'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('book-requests.my-requests', compact('requests'));
    }

    public function receivedRequests()
    {
        $requests = BookRequest::where('owner_id', Auth::id())
            ->with(['book', 'requester'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('book-requests.received-requests', compact('requests'));
    }

    public function approve(BookRequest $request)
    {
        if ($request->owner_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->update([
            'status' => 'approved',
            'approved_date' => now(),
            'return_date' => now()->addDays(14), // 2 weeks loan period
        ]);

        // Update book status to borrowed
        $request->book->update(['status' => 'borrowed']);

        return redirect()->back()->with('success', 'Book request approved!');
    }

    public function reject(BookRequest $request)
    {
        if ($request->owner_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Book request rejected.');
    }

    public function markAsReturned(BookRequest $request)
    {
        if ($request->owner_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->update([
            'status' => 'completed',
            'actual_return_date' => now(),
        ]);

        // Update book status back to available
        $request->book->update(['status' => 'available']);

        return redirect()->back()->with('success', 'Book marked as returned!');
    }

    public function cancel(BookRequest $request)
    {
        if ($request->requester_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if ($request->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot cancel non-pending request.');
        }

        $request->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Request cancelled successfully.');
    }
}
