<!-- filepath: /home/sidharth/Live/RBAC_APP/resources/views/posts/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $post->title }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('posts.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md text-sm">
                Back to Posts
            </a>
            @if($post->user_id === auth()->id())
            <a href="{{ route('posts.edit', $post) }}" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-md text-sm">
                Edit
            </a>
            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm">
                    Delete
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <div class="mb-4 flex items-center justify-between">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <span>By {{ $post->user->name }}</span>
                    <span class="mx-2">•</span>
                    <span>{{ $post->created_at->format('M d, Y') }}</span>
                </div>
                <span class="px-2 py-1 rounded text-xs {{
                    $post->status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' :
                    ($post->status === 'draft' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' :
                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300')
                }}">
                    {{ ucfirst($post->status) }}
                </span>
            </div>

            <div class="prose max-w-none dark:prose-invert mt-6">
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $post->content }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
