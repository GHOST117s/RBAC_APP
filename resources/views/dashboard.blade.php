<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold">RBAC App</h1>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="ml-3 relative">
                        <div class="flex items-center">
                            <span class="mr-2">{{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm text-red-500 hover:text-red-700">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    <h2 class="text-2xl font-bold mb-4">Dashboard</h2>
                    <p class="mb-4">Welcome to your dashboard, {{ Auth::user()->name }}!</p>

                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-3">Your Account</h3>
                        <div class="bg-gray-50 p-4 rounded">
                            <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                            <p><strong>Account Created:</strong> {{ Auth::user()->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    {{-- <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-3">Quick Actions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 p-4 rounded border border-blue-100 hover:bg-blue-100 transition-colors">
                                <h4 class="font-medium text-blue-800">Edit Profile</h4>
                                <p class="text-sm text-blue-600">Update your personal information</p>
                            </div>
                            <div class="bg-purple-50 p-4 rounded border border-purple-100 hover:bg-purple-100 transition-colors">
                                <h4 class="font-medium text-purple-800">Security Settings</h4>
                                <p class="text-sm text-purple-600">Manage passwords and security</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded border border-green-100 hover:bg-green-100 transition-colors">
                                <h4 class="font-medium text-green-800">Notifications</h4>
                                <p class="text-sm text-green-600">Control your notification preferences</p>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
