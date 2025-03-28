<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // all posts
      $posts = Post::with('user')->latest()->paginate(10);
        // $posts = Post::latest()->get();
        //
    //   dd($posts);
        // $posts = Auth::user()->posts()->latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published,archived',
        ]);

        $post = Auth::user()->posts()->create($validated);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::findOrFail($id);
        return view('posts.show', compact('post'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::findOrFail($id);

        // Check if user owns this post or has permission to edit any post
        if ($post->user_id !== Auth::id() && !Auth::user()->can('edit posts')) {
            return redirect()->route('posts.index')
                ->with('error', 'You are not authorized to edit this post.');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd(Auth::user()->can('edit posts'));
        $post = Post::findOrFail($id);

        // Check if user owns this post or has permission to edit any post
        if ($post->user_id !== Auth::id() && !Auth::user()->can('edit posts')) {
            return redirect()->route('posts.index')
                ->with('error', 'You are not authorized to update this post.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published,archived',
        ]);

        // Check publishing permission if status changed to published
        if ($validated['status'] === 'published' && $post->status !== 'published' && !Auth::user()->can('publish posts')) {
            $validated['status'] = $post->status; // Keep original status
        }

        $post->update($validated);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);

        // Check if user owns this post or has permission to delete any post
        if ($post->user_id !== Auth::id() && !Auth::user()->can('delete posts')) {
            return redirect()->route('posts.index')
                ->with('error', 'You are not authorized to delete this post.');
        }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}
