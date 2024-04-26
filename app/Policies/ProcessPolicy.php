<?php

namespace App\Policies;

use App\Models\Process;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProcessPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('browse_processes');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Process  $process
     * @return mixed
     */
    public function view(User $user, Process $process)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_processes');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Process  $process
     * @return mixed
     */
    public function update(User $user, Process $process)
    {
        return $user->hasPermission('edit_processes');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Process  $process
     * @return mixed
     */
    public function delete(User $user, Process $process)
    {
        return $user->hasPermission('delete_processes');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Process  $process
     * @return mixed
     */
    public function restore(User $user, Process $process)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Process  $process
     * @return mixed
     */
    public function forceDelete(User $user, Process $process)
    {
        //
    }
}
