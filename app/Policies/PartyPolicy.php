<?php

namespace App\Policies;

use App\Models\Party;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartyPolicy
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
       return $user->hasPermission('browse_parties');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Party  $party
     * @return mixed
     */
    public function view(User $user, Party $party)
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
       return $user->hasPermission('create_parties');

    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Party  $party
     * @return mixed
     */
    public function update(User $user, Party $party)
    {
       return $user->hasPermission('edit_parties');

    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Party  $party
     * @return mixed
     */
    public function delete(User $user, Party $party)
    {
      return  $user->hasPermission('delete_parties');

    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Party  $party
     * @return mixed
     */
    public function restore(User $user, Party $party)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Party  $party
     * @return mixed
     */
    public function forceDelete(User $user, Party $party)
    {
        //
    }
}
