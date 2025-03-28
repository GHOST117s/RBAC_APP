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
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('dashboard') }}" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="{{ route('posts.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Posts
                        </a>
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

                <!-- User Dashboard -->
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

                        <div class="mt-8">
                            <h3 class="text-lg font-semibold mb-3">Your Activity</h3>
                            <div class="bg-gray-50 p-4 rounded">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="bg-blue-50 p-4 rounded border border-blue-100">
                                        <h4 class="font-medium text-blue-800">Posts</h4>
                                        <p class="text-2xl font-bold text-blue-600">{{ Auth::user()->posts()->count() }}</p>
                                    </div>
                                    <div class="bg-purple-50 p-4 rounded border border-purple-100">
                                        <h4 class="font-medium text-purple-800">Published</h4>
                                        <p class="text-2xl font-bold text-purple-600">{{ Auth::user()->posts()->where('status', 'published')->count() }}</p>
                                    </div>
                                    <div class="bg-green-50 p-4 rounded border border-green-100">
                                        <h4 class="font-medium text-green-800">Draft</h4>
                                        <p class="text-2xl font-bold text-green-600">{{ Auth::user()->posts()->where('status', 'draft')->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-lg font-semibold">Recent Posts</h3>
                                <a href="{{ route('posts.create') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Create New Post</a>
                            </div>
                            <div class="bg-gray-50 p-4 rounded">
                                @if(Auth::user()->posts()->count() > 0)
                                    <ul class="divide-y divide-gray-200">
                                        @foreach(Auth::user()->posts()->latest()->take(5)->get() as $post)
                                        <li class="py-3">
                                            <div class="flex justify-between">
                                                <a href="{{ route('posts.show', $post) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    {{ $post->title }}
                                                </a>
                                                <span class="px-2 py-1 rounded text-xs {{
                                                    $post->status === 'published' ? 'bg-green-100 text-green-800' :
                                                    ($post->status === 'draft' ? 'bg-yellow-100 text-yellow-800' :
                                                    'bg-gray-100 text-gray-800')
                                                }}">
                                                    {{ ucfirst($post->status) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-500">{{ $post->created_at->format('M d, Y') }}</p>
                                        </li>
                                        @endforeach
                                    </ul>
                                    <div class="mt-4 text-right">
                                        <a href="{{ route('posts.index') }}" class="text-sm text-gray-600 hover:text-gray-800">View all posts →</a>
                                    </div>
                                @else
                                    <p class="text-gray-500">You haven't created any posts yet.</p>
                                    <div class="mt-4">
                                        <a href="{{ route('posts.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            Create Your First Post
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            <!-- Admin Section -->
            @if(Auth::user()->hasRole('admin'))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold text-gray-800">Admin Settings</h2>
                        <a href="{{route('users.create')}}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Create New User
                        </a>
                    </div>
                        {{-- <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            You have admin access.
                        </div> --}}
                    <!-- User Management Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Joined
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Posts
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach(\App\Models\User::withCount('posts')->get() as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $user->name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            {{ $user->email }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $user->posts_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{route('users.edit',$user)}}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        @if($user->id !== Auth::id())
                                        <a href="{{route('users.destroy',$user)}}" class="text-red-600 hover:text-red-900">Delete</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif


        </div>
    </div>
</body>
</html>
