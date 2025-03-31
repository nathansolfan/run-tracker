<?php

namespace App\Policies;

use App\Models\Run;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RunPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Run $run): bool
    {
        return $user->id === $run->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Run $run): bool
    {
        return $user->id === $run->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Run $run): bool
    {
        return $user->id === $run->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Run $run): bool
    {
        return false;
    }
}
