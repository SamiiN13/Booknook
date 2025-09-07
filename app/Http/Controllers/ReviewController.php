<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Book $book)
    {
        // Check if user already reviewed this book
        $existingReview = Review::where('book_id', $book->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this book.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'book_id' => $book->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'review' => $request->review,
            'is_verified' => false,
        ]);

        return redirect()->back()->with('success', 'Review submitted successfully!');
    }

    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return redirect()->back()->with('success', 'Review updated successfully!');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $review->delete();

        return redirect()->back()->with('success', 'Review deleted successfully!');
    }

    public function verify(Review $review)
    {
        // Only admin can verify reviews
        if (!Auth::guard('admin')->check() || Auth::guard('admin')->user()->email !== 'admin@booknook.com') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $review->update(['is_verified' => true]);

        return redirect()->back()->with('success', 'Review verified successfully!');
    }
}
