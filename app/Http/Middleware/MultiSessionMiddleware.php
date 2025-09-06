<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class MultiSessionMiddleware
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
        // Generate a unique session identifier for this tab/window
        $tabId = $request->header('X-Tab-ID') ?: $request->cookie('tab_id') ?: Str::random(32);
        
        // Set the tab ID cookie if it doesn't exist
        if (!$request->cookie('tab_id')) {
            $response = $next($request);
            return $response->cookie('tab_id', $tabId, 0, '/', null, false, false);
        }
        
        // Create a unique session key for this tab
        $sessionKey = 'user_session_' . $tabId;
        
        // Check if this tab has a stored user session
        if (Session::has($sessionKey)) {
            $userId = Session::get($sessionKey);
            
            // If the stored user is different from current auth, switch to stored user
            if (Auth::id() !== $userId) {
                Auth::loginUsingId($userId);
            }
        } else {
            // If this tab doesn't have a stored session but user is authenticated,
            // store the current user for this tab
            if (Auth::check()) {
                Session::put($sessionKey, Auth::id());
            }
        }
        
        return $next($request);
    }
}