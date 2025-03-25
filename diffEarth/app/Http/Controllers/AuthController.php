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
        $user = $request->user();
        $roles = $user->roles->pluck('name');

        return response()->json([
            'user' => $user,
            'roles' => $roles->isEmpty() ? null : $roles,
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
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if (Auth::attempt($credentials)) {
            // Authentication passed, generate a token
            $user = Auth::user();
            $token = $user->createToken('diffEarth')->plainTextToken;

            return response()->json(['token' => $token], 200);
        }
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'User has logged out']);
    }
}
