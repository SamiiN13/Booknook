<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user is admin (you can add an is_admin column to users table)
            if ($user->email === 'admin@booknook.com') {
                $request->session()->regenerate();
                return redirect()->intended('/admin/dashboard');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'You do not have admin privileges.',
                ])->withInput();
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalBooks = Book::count();
        $pendingBooks = Book::where('status', 'pending_approval')->count();
        $availableBooks = Book::where('status', 'available')->count();

        return view('admin.dashboard', compact('totalUsers', 'totalBooks', 'pendingBooks', 'availableBooks'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
} 