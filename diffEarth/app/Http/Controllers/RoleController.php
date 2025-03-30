<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Log;

// Make sure Role model is imported

class RoleController extends Controller
{
    public function getAllRoles()
    {
        $roles = Role::pluck('name'); // Get only role names
        Log::info('Roles: ', $roles->toArray());
        return response()->json($roles);
    }

    public function addRole(Request $request)
    {
        Log::info('got to addrole');
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Log::info('request validated: ', $request->toArray());

        $role = Role::create(['name' => $request->name]);

        Log::info('role created: ', $role->toArray());

        return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
    }
}
