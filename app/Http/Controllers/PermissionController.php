<?php
// filepath: /home/sidharth/Live/RBAC_APP/app/Http/Controllers/PermissionController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware
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
     * Store a newly created permission in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name'
        ]);

        Permission::create(['name' => $validated['name']]);

        return redirect()->route('admin.settings')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Update the specified permission in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id
        ]);

        // Prevent updating system permissions
        $systemPermissions = [
            'create posts', 'edit posts', 'delete posts', 'publish posts', 'unpublish posts',
            'create comments', 'edit comments', 'delete comments',
            'view users', 'create users', 'edit users', 'delete users',
            'create roles', 'edit roles', 'delete roles', 'assign roles'
        ];

        if (in_array($permission->name, $systemPermissions) && $validated['name'] !== $permission->name) {
            return redirect()->route('admin.settings')
                ->with('error', 'Cannot rename system permissions.');
        }

        $permission->name = $validated['name'];
        $permission->save();

        return redirect()->route('admin.settings')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroy(Permission $permission)
    {
        // Prevent deleting system permissions
        $systemPermissions = [
            'create posts', 'edit posts', 'delete posts', 'publish posts', 'unpublish posts',
            'create comments', 'edit comments', 'delete comments',
            'view users', 'create users', 'edit users', 'delete users',
            'create roles', 'edit roles', 'delete roles', 'assign roles'
        ];

        if (in_array($permission->name, $systemPermissions)) {
            return redirect()->route('admin.settings')
                ->with('error', 'Cannot delete system permissions.');
        }

        // Check if permission is used by any roles
        if ($permission->roles->count() > 0) {
            return redirect()->route('admin.settings')
                ->with('error', 'Cannot delete permission assigned to roles.');
        }

        $permission->delete();

        return redirect()->route('admin.settings')
            ->with('success', 'Permission deleted successfully.');
    }
}
