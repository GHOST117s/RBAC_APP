<!-- filepath: /home/sidharth/Live/RBAC_APP/resources/views/admin/settings.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Admin Settings</h1>
        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded">
            Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Roles Section -->
    <div class="bg-white shadow-md rounded-lg mb-8">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold">Role Management</h2>
            <button onclick="document.getElementById('createRoleModal').classList.remove('hidden')"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                Create Role
            </button>
        </div>
        <div class="p-4">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border text-left">Role Name</th>
                        <th class="py-2 px-4 border text-left">Permissions</th>
                        <th class="py-2 px-4 border text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\Spatie\Permission\Models\Role::with('permissions')->get() as $role)
                    <tr class="border-b">
                        <td class="py-2 px-4 border">{{ ucfirst($role->name) }}</td>
                        <td class="py-2 px-4 border">
                            <div class="flex flex-wrap gap-1">
                                @foreach($role->permissions->take(3) as $permission)
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 text-xs rounded">
                                        {{ $permission->name }}
                                    </span>
                                @endforeach
                                @if($role->permissions->count() > 3)
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 text-xs rounded">
                                        +{{ $role->permissions->count() - 3 }} more
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="py-2 px-4 border">
                            <button onclick="editRole('{{ $role->id }}', '{{ $role->name }}', {{ json_encode($role->permissions->pluck('id')) }})"
                                    class="text-blue-600 hover:text-blue-800 mr-2">
                                Edit
                            </button>

                            @if(!in_array($role->name, ['super-admin', 'admin', 'user']))
                            <form method="POST" action="{{ route('admin.roles.destroy', $role->id) }}" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this role?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    Delete
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Permissions Section -->
    <div class="bg-white shadow-md rounded-lg">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold">Permission Management</h2>
            <button onclick="document.getElementById('createPermissionModal').classList.remove('hidden')"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                Create Permission
            </button>
        </div>
        <div class="p-4">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border text-left">Permission Name</th>
                        <th class="py-2 px-4 border text-left">Used By Roles</th>
                        <th class="py-2 px-4 border text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\Spatie\Permission\Models\Permission::with('roles')->get() as $permission)
                    <tr class="border-b">
                        <td class="py-2 px-4 border">{{ $permission->name }}</td>
                        <td class="py-2 px-4 border">
                            <div class="flex flex-wrap gap-1">
                                @foreach($permission->roles->take(3) as $role)
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 text-xs rounded">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                                @if($permission->roles->count() > 3)
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 text-xs rounded">
                                        +{{ $permission->roles->count() - 3 }} more
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="py-2 px-4 border">
                            <button onclick="editPermission('{{ $permission->id }}', '{{ $permission->name }}')"
                                    class="text-blue-600 hover:text-blue-800 mr-2">
                                Edit
                            </button>

                            @php
                                $systemPermissions = [
                                    'create posts', 'edit posts', 'delete posts', 'publish posts', 'unpublish posts',
                                    'create comments', 'edit comments', 'delete comments',
                                    'view users', 'create users', 'edit users', 'delete users',
                                    'create roles', 'edit roles', 'delete roles', 'assign roles'
                                ];
                            @endphp

                            @if(!in_array($permission->name, $systemPermissions))
                            <form method="POST" action="{{ route('admin.permissions.destroy', $permission->id) }}" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this permission?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    Delete
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Role Modal -->
<div id="createRoleModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="roleForm" method="POST" action="{{ route('admin.roles.store') }}">
                @csrf
                <div id="roleMethodField"></div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="roleModalTitle">Create New Role</h3>

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Role Name</label>
                        <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assign Permissions</label>
                        <div class="max-h-60 overflow-y-auto border rounded-md p-2">
                            @foreach(\Spatie\Permission\Models\Permission::all() as $permission)
                            <div class="flex items-center mb-2">
                                <input type="checkbox" name="permissions[]" id="permission-{{ $permission->id }}" value="{{ $permission->id }}"
                                       class="rounded border-gray-300 text-blue-600">
                                <label for="permission-{{ $permission->id }}" class="ml-2 text-sm text-gray-700">
                                    {{ $permission->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        <span id="roleSubmitButtonText">Create</span>
                    </button>
                    <button type="button" onclick="closeRoleModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Permission Modal -->
<div id="createPermissionModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="permissionForm" method="POST" action="{{ route('admin.permissions.store') }}">
                @csrf
                <div id="permMethodField"></div>

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="permModalTitle">Create New Permission</h3>

                    <div class="mb-4">
                        <label for="perm_name" class="block text-sm font-medium text-gray-700">Permission Name</label>
                        <input type="text" name="name" id="perm_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <p class="mt-1 text-sm text-gray-500">Use descriptive names like "edit posts" or "delete users"</p>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        <span id="permSubmitButtonText">Create</span>
                    </button>
                    <button type="button" onclick="closePermissionModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Role modal functions
    function editRole(id, name, permissions) {
        // Update form for editing
        document.getElementById('roleModalTitle').innerText = 'Edit Role';
        document.getElementById('roleForm').action = '/admin/roles/' + id;
        document.getElementById('roleMethodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('name').value = name;
        document.getElementById('roleSubmitButtonText').innerText = 'Update';

        // Reset permissions checkboxes
        document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });

        // Check permissions for this role
        permissions.forEach(id => {
            const checkbox = document.getElementById('permission-' + id);
            if (checkbox) checkbox.checked = true;
        });

        // Show modal
        document.getElementById('createRoleModal').classList.remove('hidden');
    }

    function closeRoleModal() {
        // Reset form and hide modal
        document.getElementById('roleModalTitle').innerText = 'Create New Role';
        document.getElementById('roleForm').action = '{{ route('admin.roles.store') }}';
        document.getElementById('roleMethodField').innerHTML = '';
        document.getElementById('name').value = '';
        document.getElementById('roleSubmitButtonText').innerText = 'Create';

        document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });

        document.getElementById('createRoleModal').classList.add('hidden');
    }

    // Permission modal functions
    function editPermission(id, name) {
        // Update form for editing
        document.getElementById('permModalTitle').innerText = 'Edit Permission';
        document.getElementById('permissionForm').action = '/admin/permissions/' + id;
        document.getElementById('permMethodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('perm_name').value = name;
        document.getElementById('permSubmitButtonText').innerText = 'Update';

        // Show modal
        document.getElementById('createPermissionModal').classList.remove('hidden');
    }

    function closePermissionModal() {
        // Reset form and hide modal
        document.getElementById('permModalTitle').innerText = 'Create New Permission';
        document.getElementById('permissionForm').action = '{{ route('admin.permissions.store') }}';
        document.getElementById('permMethodField').innerHTML = '';
        document.getElementById('perm_name').value = '';
        document.getElementById('permSubmitButtonText').innerText = 'Create';

        document.getElementById('createPermissionModal').classList.add('hidden');
    }
</script>
@endsection
