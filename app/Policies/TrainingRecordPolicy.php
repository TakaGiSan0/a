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
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TrainingRecord $trainingRecord): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TrainingRecord $trainingRecord): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, training_record $trainingRecord): bool
    {
        //
        return $user->role === 'super admin';
    }


    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TrainingRecord $trainingRecord): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TrainingRecord $trainingRecord): bool
    {
        //
    }
}
