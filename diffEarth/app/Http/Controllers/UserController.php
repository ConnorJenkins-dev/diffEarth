<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
