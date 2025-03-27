<!-- filepath: /home/sidharth/Live/RBAC_APP/resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }} - All Posts</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen">
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="flex-shrink-0 flex items-center">
                                <h1 class="text-xl font-bold text-gray-800 dark:text-white">{{ config('app.name', 'Laravel') }}</h1>
                            </div>
                        </div>
                        <div class="flex items-center">
                            @if (Route::has('login'))
                                <div class="space-x-4">
                                    @auth
                                        <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-300 underline">Dashboard</a>
                                    @else
                                        <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-300 underline">Log in</a>

                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-300 underline">Register</a>
                                        @endif
                                    @endauth
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </nav>

            <main class="py-8">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="mb-6 flex justify-between items-center">
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">All Posts</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach(\App\Models\Post::with('user')->latest()->get() as $post)
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                                <div class="p-6">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ $post->title }}</h3>

                                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-3 flex items-center justify-between">
                                        <span>By {{ $post->user->name }}</span>
                                        <span class="px-2 py-1 rounded text-xs {{
                                            $post->status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' :
                                            ($post->status === 'draft' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' :
                                            'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300')
                                        }}">
                                            {{ ucfirst($post->status) }}
                                        </span>
                                    </div>

                                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                                        {{ Str::limit($post->content, 150) }}
                                    </p>

                                    <div class="mt-4 flex justify-end">
                                        <a href="#" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500">
                                            Read more →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
