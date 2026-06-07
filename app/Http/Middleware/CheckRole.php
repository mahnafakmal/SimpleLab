<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Usage: middleware('role:admin,dosen')
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        $user = Auth::user();
        if (! $user) {
            abort(403, 'Unauthorized');
        }

        $allowed = array_map('trim', explode(',', $roles));
        if (! in_array($user->role, $allowed)) {
            abort(403, 'Access denied for your role');
        }

        return $next($request);
    }
}
