<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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

    public function getAllEmails()
    {
        // Get all emails in the 'users' table
        $emails = User::pluck('email');
        return response()->json($emails);
    }
}
