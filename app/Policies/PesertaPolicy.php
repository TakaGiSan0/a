<?php

namespace App\Policies;

use App\Models\Peserta;
use App\Models\User;
use Illuminate\Auth\Access\Response;


class PesertaPolicy
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
    public function view(User $user, Peserta $peserta): void
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['Super Admin', 'Admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    /**
     * Determine whether the user can update the model.
     *
     * Hanya izinkan pengguna dengan role 'superadmin' untuk mengupdate
     * peserta.
     */
    public function update(User $user, Peserta $peserta): bool
    {
        return in_array($user->role, ['Super Admin', 'Admin']);
        
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Peserta $peserta): bool
    {
        return in_array($user->role, ['Super Admin', 'Admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Peserta $peserta): void
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Peserta $peserta): void
    {
        //
    }
}
