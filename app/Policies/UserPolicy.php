<?php
// filepath: /home/sidharth/Live/RBAC_APP/app/Policies/UserPolicy.php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view users');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Users can always view their own profiles
        if ($user->id === $model->id) {
            return true;
        }

        return $user->hasPermissionTo('view users');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create users');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Users can always edit their own profiles
        if ($user->id === $model->id) {
            return true;
        }

        // Cannot edit users with higher roles
        if ($model->hasRole('super-admin') && !$user->hasRole('super-admin')) {
            return false;
        }

        if ($model->hasRole('admin') && !$user->hasAnyRole(['super-admin', 'admin'])) {
            return false;
        }

        return $user->hasPermissionTo('edit users');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Users cannot delete themselves
        if ($user->id === $model->id) {
            return false;
        }

        // Cannot delete users with higher roles
        if ($model->hasRole('super-admin') && !$user->hasRole('super-admin')) {
            return false;
        }

        if ($model->hasRole('admin') && !$user->hasAnyRole(['super-admin', 'admin'])) {
            return false;
        }

        return $user->hasPermissionTo('delete users');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->hasPermissionTo('edit users');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('delete users');
    }

    /**
     * Determine whether the user can assign roles to the model.
     */
    public function assignRole(User $user, User $model): bool
    {
        // Need permission to assign roles
        if (!$user->hasPermissionTo('assign roles')) {
            return false;
        }

        // Cannot assign roles to users with higher roles
        if ($model->hasRole('super-admin') && !$user->hasRole('super-admin')) {
            return false;
        }

        if ($model->hasRole('admin') && !$user->hasAnyRole(['super-admin', 'admin'])) {
            return false;
        }

        return true;
    }
}
