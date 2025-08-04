<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::where('status', 'available')->with('user')->paginate(12);
        return view('books.index', compact('books'));
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
            'genre' => 'nullable|string|max:100',
            'condition' => 'required|in:new,like_new,good,fair,poor',
            'published_year' => 'nullable|integer|min:1800|max:' . (date('Y') + 1),
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending_approval';

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