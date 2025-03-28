<?php
// filepath: /home/sidharth/Live/RBAC_APP/app/Http/Controllers/RoleController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create(['name' => $validated['name']]);

        if (isset($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('admin.settings')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
        ]);

        // Prevent updating system roles
        if (in_array($role->name, ['super-admin', 'admin', 'editor', 'author', 'user']) && $validated['name'] !== $role->name) {
            return redirect()->route('admin.settings')
                ->with('error', 'Cannot rename system roles.');
        }

        $role->name = $validated['name'];
        $role->save();

        if (isset($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('admin.settings')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        // Prevent deleting system roles
        if (in_array($role->name, ['super-admin', 'admin', 'editor', 'author', 'user'])) {
            return redirect()->route('admin.settings')
                ->with('error', 'Cannot delete system roles.');
        }

        // Check if role has users
        if ($role->users->count() > 0) {
            return redirect()->route('admin.settings')
                ->with('error', 'Cannot delete role that has users assigned.');
        }

        $role->syncPermissions([]); // Remove all permissions
        $role->delete();

        return redirect()->route('admin.settings')
            ->with('success', 'Role deleted successfully.');
    }
}
