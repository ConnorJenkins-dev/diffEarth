<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        // Log the incoming user and their roles for debugging
        Log::info('User trying to access route', [
            'user_id' => $user ? $user->id : null,
            'user_roles' => $user ? $user->roles->pluck('name') : 'No user',
            'required_roles' => $roles,
        ]);

        if (!$user || !$user->roles()->whereIn('name', $roles)->exists()) {
            // Log when access is denied
            Log::warning('Access denied for user', [
                'user_id' => $user ? $user->id : null,
                'user_roles' => $user ? $user->roles->pluck('name') : 'No user',
                'required_roles' => $roles,
            ]);

            return response()->json([
                'error' => 'Forbidden',
                'message' => 'You do not have the required role: ' . implode(', ', $roles)
            ], 403);
        }

        // Log successful access
        Log::info('Access granted to user', [
            'user_id' => $user->id,
            'user_roles' => $user->roles->pluck('name'),
            'required_roles' => $roles,
        ]);

        return $next($request);
    }
}
