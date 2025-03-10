<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Get the authenticated user.
     */
    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
            'roles' => $request->user()->roles->pluck('name'),
            //this will break if a user doesn't have a role but i dunno how to handle that
        ]);
    }

    public function hasRole(Request $request, $role)
    {
        if ($request->user()->hasRole($role)) {
            return response()->json(['message' => 'User has role: ' . $role], 200);
        }
        return response()->json(['message' => 'User does not have role: ' . $role], 403);
    }

    public function login(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'User has logged out']);
    }
}
