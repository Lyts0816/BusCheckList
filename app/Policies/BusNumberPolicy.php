<?php

namespace App\Policies;

use App\Models\BusNumber;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BusNumberPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isAdminOperations() || $user->isOperations();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BusNumber $busNumber): bool
    {
        return $user->isAdmin() || $user->isAdminOperations() || $user->isOperations();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
       return $user->isAdmin() || $user->isAdminOperations() || $user->isOperations();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BusNumber $busNumber): bool
    {
        return $user->isAdmin() || $user->isAdminOperations() || $user->isOperations();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BusNumber $busNumber): bool
    {
        return $user->isAdmin() || $user->isAdminOperations() || $user->isOperations();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BusNumber $busNumber): bool
    {
        return $user->isAdmin() || $user->isAdminOperations() || $user->isOperations();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BusNumber $busNumber): bool
    {
        return $user->isAdmin() || $user->isAdminOperations() || $user->isOperations();
    }

    /**
     * Determine whether the user can import bus numbers.
     */
    public function import(User $user): bool
    {
        return $user->isAdmin() || $user->isAdminOperations();
    }
}
