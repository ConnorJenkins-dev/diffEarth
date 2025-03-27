<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function getRoles(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'user' => $request->user()->only(['id', 'name', 'email']),
            'roles' => $request->user()->roles->pluck('name'),
        ]);
    }

    public function getUser(Request $request)
    {
        return response()->json([
            'user' => $request->user()->only(['id', 'name', 'email']),
        ]);
    }

    public function getAllEmails()
    {
        $emails = User::pluck('email'); // Get all user emails
        return response()->json($emails);
    }

    public function updateUserRole(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'roles' => 'required|array',
            'roles.*' => 'required|string|exists:roles,name',
        ]);

        // Get the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Get role IDs from the roles' names
        $roleIds = Role::whereIn('name', $request->roles)->pluck('id')->toArray();

        // Detach existing roles and attach the new roles by role_id
        $user->roles()->sync($roleIds);

        return response()->json(['message' => 'Roles updated successfully']);
    }
}
