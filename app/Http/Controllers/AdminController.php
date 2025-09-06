<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\BookRequest;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        if (Auth::attempt($credentials)) {
            if (Auth::user()->email === 'admin@booknook.com') {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'user' => [
                            'id' => Auth::id(),
                            'name' => Auth::user()->name,
                            'email' => Auth::user()->email
                        ],
                        'redirect' => '/admin/dashboard'
                    ]);
                }
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Access denied. Admin only.'
                    ], 403);
                }
                return redirect()->back()->withErrors(['email' => 'Access denied. Admin only.']);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.'
            ], 401);
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function dashboard()
    {
        if (Auth::user()->email !== 'admin@booknook.com') {
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
        if (Auth::user()->email !== 'admin@booknook.com') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $book->update(['status' => 'available']);
        return redirect()->back()->with('success', 'Book approved successfully!');
    }

    public function rejectBook(Book $book)
    {
        if (Auth::user()->email !== 'admin@booknook.com') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $book->delete();
        return redirect()->back()->with('success', 'Book rejected and removed.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
} 