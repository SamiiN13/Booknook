<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Book routes (public)
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/my-books', [BookController::class, 'myBooks'])->name('books.my-books');
});

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login']);
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/books', [BookController::class, 'adminIndex'])->name('admin.books.index');
        Route::patch('/books/{book}/approve', [BookController::class, 'approve'])->name('admin.books.approve');
        Route::delete('/books/{book}/reject', [BookController::class, 'reject'])->name('admin.books.reject');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    });
});
