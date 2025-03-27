<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        // Post permissions
        Permission::create(['name' => 'create posts']);
        Permission::create(['name' => 'edit posts']);
        Permission::create(['name' => 'delete posts']);
        Permission::create(['name' => 'publish posts']);
        Permission::create(['name' => 'unpublish posts']);

        // Comment permissions
        Permission::create(['name' => 'create comments']);
        Permission::create(['name' => 'edit comments']);
        Permission::create(['name' => 'delete comments']);

        // User permissions
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        // Role permissions
        Permission::create(['name' => 'create roles']);
        Permission::create(['name' => 'edit roles']);
        Permission::create(['name' => 'delete roles']);
        Permission::create(['name' => 'assign roles']);

        // Create roles and assign permissions
        // 1. Super Admin - has all permissions
        $superAdminRole = Role::create(['name' => 'super-admin']);
        // Super admin gets all permissions
        $superAdminRole->givePermissionTo(Permission::all());

        // 2. Admin - has most permissions but limited
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'create posts', 'edit posts', 'delete posts', 'publish posts', 'unpublish posts',
            'create comments', 'edit comments', 'delete comments',
            'view users', 'create users', 'edit users',
        ]);

        // 3. Editor - can manage content
        $editorRole = Role::create(['name' => 'editor']);
        $editorRole->givePermissionTo([
            'create posts', 'edit posts', 'publish posts', 'unpublish posts',
            'create comments', 'edit comments',
        ]);

        // 4. Author - can create and manage their own content
        $authorRole = Role::create(['name' => 'author']);
        $authorRole->givePermissionTo([
            'create posts', 'edit posts',
            'create comments',
        ]);

        // 5. User - basic permissions
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'create comments',
        ]);

        // Create a super admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );
        $superAdmin->assignRole('super-admin');

        // Create an admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole('admin');

        // Create an editor user
        $editor = User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor User',
                'password' => bcrypt('password'),
            ]
        );
        $editor->assignRole('editor');

        // Create an author user
        $author = User::firstOrCreate(
            ['email' => 'author@example.com'],
            [
                'name' => 'Author User',
                'password' => bcrypt('password'),
            ]
        );
        $author->assignRole('author');

        // Assign regular user role to all other existing users
        User::whereNotIn('email', [
            'superadmin@example.com',
            'admin@example.com',
            'editor@example.com',
            'author@example.com',
        ])->each(function ($user) use ($userRole) {
            $user->assignRole('user');
        });
    }
}
