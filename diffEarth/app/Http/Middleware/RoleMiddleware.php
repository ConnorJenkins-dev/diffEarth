<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if the user is authenticated and has the required role, now works for multiple roles
        if (!$request->user() || !$request->user()->hasRole($role)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
