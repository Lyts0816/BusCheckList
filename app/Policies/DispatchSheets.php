<?php

namespace App\Policies;

use App\Models\DispatchSheet;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DispatchSheets
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isOperations() || $user->isAdminOperations();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DispatchSheet $dispatchSheet): bool
    {
        return $user->isAdmin() || $user->isOperations() || $user->isAdminOperations();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isOperations() || $user->isAdminOperations();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DispatchSheet $dispatchSheet): bool
    {
        return $user->isAdmin() || $user->isOperations() || $user->isAdminOperations();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DispatchSheet $dispatchSheet): bool
    {
        return $user->isAdmin() || $user->isOperations() || $user->isAdminOperations();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DispatchSheet $dispatchSheet): bool
    {
        return $user->isAdmin() || $user->isOperations() || $user->isAdminOperations();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DispatchSheet $dispatchSheet): bool
    {
        return $user->isAdmin() || $user->isOperations() || $user->isAdminOperations();
    }
}
