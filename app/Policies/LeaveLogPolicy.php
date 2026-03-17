<?php

namespace App\Policies;

use App\Models\LeaveLog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LeaveLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->canViewleaveModule();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LeaveLog $leaveLog): bool
    {
        return $user->canViewleaveModule();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->canViewleaveModule();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LeaveLog $leaveLog): bool
    {
        return $user->canViewleaveModule();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LeaveLog $leaveLog): bool
    {
        return $user->hasAdminLeaveModule();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LeaveLog $leaveLog): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LeaveLog $leaveLog): bool
    {
        return false;
    }
}
