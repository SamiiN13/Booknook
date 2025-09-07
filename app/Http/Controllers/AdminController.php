<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\BookRequest;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            if (Auth::guard('admin')->user()->email === 'admin@booknook.com') {
                $request->session()->regenerate();
                Session::put('user_type', 'admin');
                Session::put('user_id', Auth::guard('admin')->id());
                return redirect()->route('admin.dashboard');
            } else {
                Auth::guard('admin')->logout();
                return redirect()->back()->withErrors(['email' => 'Access denied. Admin only.']);
            }
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function dashboard()
    {
        if (!Auth::guard('admin')->check() || Auth::guard('admin')->user()->email !== 'admin@booknook.com') {
            return redirect()->route('books.index');
        }

        $stats = [
            'total_users' => User::count(),
            'total_books' => Book::count(),
            'pending_books' => Book::where('status', 'pending_approval')->count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'active_requests' => BookRequest::whereIn('status', ['pending', 'approved'])->count(),
        ];

        $pendingBooks = Book::where('status', 'pending_approval')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        $pendingReports = Report::where('status', 'pending')
            ->with(['reporter', 'reportable'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingBooks', 'pendingReports'));
    }

    public function approveBook(Book $book)
    {
        if (!Auth::guard('admin')->check() || Auth::guard('admin')->user()->email !== 'admin@booknook.com') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $book->update(['status' => 'available']);
        return redirect()->back()->with('success', 'Book approved successfully!');
    }

    public function rejectBook(Book $book)
    {
        if (!Auth::guard('admin')->check() || Auth::guard('admin')->user()->email !== 'admin@booknook.com') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $book->delete();
        return redirect()->back()->with('success', 'Book rejected and removed.');
    }

    public function users()
    {
        if (!Auth::guard('admin')->check() || Auth::guard('admin')->user()->email !== 'admin@booknook.com') {
            return redirect()->route('books.index');
        }

        $users = User::where('email', '!=', 'admin@booknook.com')
            ->withCount(['books', 'reviews'])
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function allBooks()
    {
        if (!Auth::guard('admin')->check() || Auth::guard('admin')->user()->email !== 'admin@booknook.com') {
            return redirect()->route('books.index');
        }

        $books = Book::with(['user', 'reviews'])
            ->withCount('reviews')
            ->latest()
            ->paginate(20);

        return view('admin.books.all', compact('books'));
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
} 