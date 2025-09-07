<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookRequestController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test');
})->name('test');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Book routes (public)
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->whereNumber('book')->name('books.show');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Book management
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::get('/my-books', [BookController::class, 'myBooks'])->name('books.my-books');
    
    // Book requests
    Route::post('/books/{book}/request', [BookRequestController::class, 'store'])->name('books.request');
    Route::get('/my-requests', [BookRequestController::class, 'myRequests'])->name('book-requests.my-requests');
    Route::get('/received-requests', [BookRequestController::class, 'receivedRequests'])->name('book-requests.received');
    Route::patch('/requests/{request}/approve', [BookRequestController::class, 'approve'])->name('book-requests.approve');
    Route::patch('/requests/{request}/reject', [BookRequestController::class, 'reject'])->name('book-requests.reject');
    Route::patch('/requests/{request}/return', [BookRequestController::class, 'markAsReturned'])->name('book-requests.return');
    Route::delete('/requests/{request}/cancel', [BookRequestController::class, 'cancel'])->name('book-requests.cancel');
    
    // Reviews
    Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Reports
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login']);
    
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Book management
        Route::get('/books', [BookController::class, 'adminIndex'])->name('admin.books.index');
        Route::patch('/books/{book}/approve', [BookController::class, 'approve'])->name('admin.books.approve');
        Route::delete('/books/{book}/reject', [BookController::class, 'reject'])->name('admin.books.reject');
        
        // Reports management
        Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/reports/{report}', [ReportController::class, 'show'])->name('admin.reports.show');
        Route::patch('/reports/{report}', [ReportController::class, 'update'])->name('admin.reports.update');
        Route::delete('/reports/{report}/item', [ReportController::class, 'deleteReportedItem'])->name('admin.reports.delete-item');
        
        // Review verification
        Route::patch('/reviews/{review}/verify', [ReviewController::class, 'verify'])->name('admin.reviews.verify');
        
        // User management
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
        Route::get('/books/all', [AdminController::class, 'allBooks'])->name('admin.books.all');
        
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    });
});
