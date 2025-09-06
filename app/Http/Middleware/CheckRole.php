<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$role): Response
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized access');
        }
        $userRole = Auth::user()->role;
        
        // Check if user role is in allowed role
        if (in_array($userRole, $role)) {
            return $next($request);
        }

        abort(403, 'Unauthorized access');
    }
}
