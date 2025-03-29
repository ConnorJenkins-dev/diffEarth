<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

    public function register(Request $request)
    {
        Log::info("User Registered");

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign the 'user' role
        $role = Role::where('name', 'user')->first();
        $user->roles()->attach($role);

        // Log the user in
        $credentials = ['email' => $request->email, 'password' => $request->password];

        if (Auth::attempt($credentials)) {
            $user = Auth::user(); // Retrieve the logged-in user
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'message' => 'User registered and logged in successfully',
                'token' => $token,
                'user' => $user,
                'role' => $role->name
            ], 201);
        }

        return response()->json(['message' => 'Failed to log in after registration.'], 500);
    }
}
