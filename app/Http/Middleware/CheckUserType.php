<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user->type === 'A') {
            // If user type is A, proceed with the original request
            return $next($request);
        } else {
            // If user type is not A, redirect to a different route or return an error response
            return response()->json(['error' => 'Unauthorized. User type is not Admin.'], 403);
        }
    }
}
