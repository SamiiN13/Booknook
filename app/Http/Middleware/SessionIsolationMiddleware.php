<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SessionIsolationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip middleware for login, register, and logout routes
        $skipRoutes = ['login', 'register', 'logout', 'admin.login', 'admin.logout'];
        if (in_array($request->route()?->getName(), $skipRoutes)) {
            return $next($request);
        }

        // Get the current session ID
        $currentSessionId = $request->session()->getId();
        
        // Check if this session has a stored user type
        $sessionUserType = Session::get('user_type');
        $sessionUserId = Session::get('user_id');
        
        // If user is authenticated, ensure session consistency
        if (Auth::check()) {
            $currentUser = Auth::user();
            
            // Check if the session user matches the authenticated user
            if ($sessionUserId !== $currentUser->id) {
                // Session mismatch - clear and reset
                Session::flush();
                Auth::logout();
                return redirect()->route('login')->with('error', 'Session expired. Please login again.');
            }
            
            // Set user type based on email
            $userType = $currentUser->email === 'admin@booknook.com' ? 'admin' : 'user';
            
            // Store user type and ID in session
            Session::put('user_type', $userType);
            Session::put('user_id', $currentUser->id);
            
            // Redirect admin users away from user routes
            if ($userType === 'admin' && $this->isUserRoute($request)) {
                return redirect()->route('admin.dashboard');
            }
            
            // Redirect regular users away from admin routes
            if ($userType === 'user' && $this->isAdminRoute($request)) {
                return redirect()->route('dashboard');
            }
        } else {
            // User not authenticated, clear session data
            Session::forget(['user_type', 'user_id']);
        }
        
        return $next($request);
    }
    
    private function isUserRoute(Request $request): bool
    {
        $userRoutes = [
            'books.create', 'books.my-books', 'book-requests.my-requests', 
            'book-requests.received', 'dashboard'
        ];
        
        return in_array($request->route()?->getName(), $userRoutes);
    }
    
    private function isAdminRoute(Request $request): bool
    {
        return str_starts_with($request->route()?->getName() ?? '', 'admin.');
    }
}
