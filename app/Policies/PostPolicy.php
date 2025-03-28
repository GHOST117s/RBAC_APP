<?php
// filepath: /home/sidharth/Live/RBAC_APP/app/Policies/PostPolicy.php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view posts');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        // Users can always view their own posts
        if ($user->id === $post->user_id) {
            return true;
        }

        // Published posts can be viewed by anyone with view permission
        if ($post->status === 'published' && $user->hasPermissionTo('view posts')) {
            return true;
        }

        // Draft posts can only be viewed by editors or admins
        return $user->hasPermissionTo('edit posts');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create posts');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        // Users can always edit their own posts
        if ($user->id === $post->user_id) {
            return true;
        }

        // Otherwise check for edit permission
        return $user->hasPermissionTo('edit posts');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        // Users can delete their own posts
        if ($user->id === $post->user_id) {
            return true;
        }

        // Otherwise check for delete permission
        return $user->hasPermissionTo('delete posts');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        return $user->hasPermissionTo('edit posts');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return $user->hasPermissionTo('delete posts');
    }

    /**
     * Determine whether the user can publish posts.
     */
    public function publish(User $user, Post $post): bool
    {
        return $user->hasPermissionTo('publish posts');
    }
}
