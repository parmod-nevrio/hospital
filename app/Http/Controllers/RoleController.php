<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with(['permissions', 'users'])->get();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'required|string',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'description' => $validated['description']
        ]);

        // If the role is admin or super-admin, assign all permissions
        if (strtolower($role->name) === 'admin' || strtolower($role->name) === 'super admin') {
            $allPermissions = Permission::all()->pluck('id')->toArray();
            $role->permissions()->attach($allPermissions);
        } else {
            $role->permissions()->attach($validated['permissions']);
        }

        return response()->json($role->load(['permissions', 'users']), 201);
    }

    public function show(Role $role)
    {
        return response()->json($role->load(['permissions', 'users']));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'sometimes|required|string',
            'permissions' => 'sometimes|required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->update([
            'name' => $validated['name'] ?? $role->name,
            'description' => $validated['description'] ?? $role->description
        ]);

        // If the role is admin or super-admin, assign all permissions
        if (strtolower($role->name) === 'admin' || strtolower($role->name) === 'super admin') {
            $allPermissions = Permission::all()->pluck('id')->toArray();
            $role->permissions()->sync($allPermissions);
        } elseif (isset($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return response()->json($role->load(['permissions', 'users']));
    }

    public function destroy(Role $role)
    {
        $role->permissions()->detach();
        $role->delete();
        return response()->json(null, 204);
    }
}
