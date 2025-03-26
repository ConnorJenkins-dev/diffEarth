<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');

        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        // Attempt to authenticate user
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            Log::info("User logged in:", ['email' => $user->email, 'role' => $user->roles->pluck('name')]);

            return response()->json([
                'user' => $user->only(['id', 'name', 'email']),
                'token' => $token,
                'role' => $user->roles->first()->name ?? 'user',
            ]);
        }

        Log::warning("Login Failed for email: " . $request->email);
        return response()->json(['message' => 'Invalid email or password'], 401);
    }

    public function logout(Request $request)
    {
        // Revoke the user's current token
        $request->user()->tokens()->delete();
        Log::info("User logged out");

        return response()->json(['message' => 'Logged out successfully']);
    }
}
