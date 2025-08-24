<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::where('status', 'available')->with(['user', 'reviews']);

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by genre
        if ($request->filled('genre')) {
            $query->byGenre($request->genre);
        }

        // Filter by rarity
        if ($request->filled('rarity')) {
            $query->byRarity($request->rarity);
        }

        // Filter by trust score (only show books user can access)
        if (Auth::check()) {
            $userTrustScore = Auth::user()->trust_score;
            $query->byTrustScore($userTrustScore);
        } else {
            // For non-authenticated users, only show common books
            $query->byTrustScore(0);
        }

        // Sort options
        switch ($request->get('sort', 'newest')) {
            case 'title':
                $query->orderBy('title');
                break;
            case 'author':
                $query->orderBy('author');
                break;
            case 'rating':
                $query->orderBy('id'); // Will be sorted by average rating in view
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $books = $query->paginate(12)->withQueryString();

        // Get available genres for filter
        $genres = Book::where('status', 'available')->distinct()->pluck('genre')->filter();

        return view('books.index', compact('books', 'genres'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'genre' => 'required|string|max:100',
            'condition' => 'required|in:new,like_new,good,fair,poor',
            'published_year' => 'nullable|integer|min:1800|max:' . (date('Y') + 1),
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:50',
            'rarity' => 'required|in:common,uncommon,rare,very_rare',
            'max_loan_duration' => 'required|integer|min:1|max:90',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending_approval';

        // Set minimum trust score based on rarity
        switch ($request->rarity) {
            case 'very_rare':
                $data['min_trust_score'] = 90;
                break;
            case 'rare':
                $data['min_trust_score'] = 75;
                break;
            case 'uncommon':
                $data['min_trust_score'] = 50;
                break;
            case 'common':
            default:
                $data['min_trust_score'] = 0;
                break;
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('books', 'public');
            $data['image_path'] = $imagePath;
        }

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Book added successfully! It will be reviewed by admin.');
    }

    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    public function myBooks()
    {
        $books = Book::where('user_id', Auth::id())->paginate(10);
        return view('books.my-books', compact('books'));
    }

    // Admin methods
    public function adminIndex()
    {
        $pendingBooks = Book::where('status', 'pending_approval')->with('user')->paginate(10);
        return view('admin.books.index', compact('pendingBooks'));
    }

    public function approve(Book $book)
    {
        $book->update(['status' => 'available']);
        return redirect()->back()->with('success', 'Book approved successfully!');
    }

    public function reject(Book $book)
    {
        $book->delete();
        return redirect()->back()->with('success', 'Book rejected and removed.');
    }
} 