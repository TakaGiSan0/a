<?php

namespace App\Policies;

use App\Models\training_record;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TrainingRecordPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): void
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, training_record $trainingRecord): void
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): void
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Training_Record $trainingRecord): void
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, training_record $trainingRecord): bool
    {
        return in_array($user->role, ['Super Admin', 'Admin']);
       
    }


    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Training_Record $trainingRecord): void
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Training_Record $trainingRecord): void
    {
        //
    }
}
