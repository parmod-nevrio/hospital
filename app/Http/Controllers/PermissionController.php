<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('roles')->get();
        return response()->json($permissions);
    }

    public function show(Permission $permission)
    {
        return response()->json($permission->load('roles'));
    }

    public function updateRoles(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);

        $permission->roles()->sync($validated['roles']);

        return response()->json($permission->load('roles'));
    }
}
