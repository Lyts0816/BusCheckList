<?php

namespace App\Policies;

use App\Models\Shipment;
use App\Models\User;

class ShipmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasSuperAdminLeaveModule();
    }

    public function view(User $user, Shipment $shipment): bool
    {
        return $user->hasSuperAdminLeaveModule();
    }

    public function create(User $user): bool
    {
        return $user->hasSuperAdminLeaveModule();
    }

    public function update(User $user, Shipment $shipment): bool
    {
        return $user->hasSuperAdminLeaveModule();
    }

    public function delete(User $user, Shipment $shipment): bool
    {
        return $user->hasSuperAdminLeaveModule();
    }

    public function restore(User $user, Shipment $shipment): bool
    {
        return $user->hasSuperAdminLeaveModule();
    }

    public function forceDelete(User $user, Shipment $shipment): bool
    {
        return $user->hasSuperAdminLeaveModule();
    }
}
