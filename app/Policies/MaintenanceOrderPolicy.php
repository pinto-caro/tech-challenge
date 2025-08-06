<?php

namespace App\Policies;

use App\Models\MaintenanceOrder;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MaintenanceOrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSupervisor() || $user->isTechnician();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MaintenanceOrder $maintenanceOrder): bool
    {
        return $user->isSupervisor() || $user->id === $maintenanceOrder->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isSupervisor();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MaintenanceOrder $maintenanceOrder): bool
    {
        if ($user->isSupervisor()) {
            return !in_array($maintenanceOrder->status, ['in_progress', 'finalized']);
        }

        if ($user->isTechnician()) {
            return $user->id === $maintenanceOrder->user_id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MaintenanceOrder $maintenanceOrder): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MaintenanceOrder $maintenanceOrder): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MaintenanceOrder $maintenanceOrder): bool
    {
        return false;
    }
}
