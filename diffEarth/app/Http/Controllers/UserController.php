<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getRoles(Request $request)
    {
        // Return the roles of the authenticated user
        return response()->json($request->user()->roles);
    }
}
