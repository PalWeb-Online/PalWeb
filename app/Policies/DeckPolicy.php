<?php

namespace App\Policies;

use App\Models\Deck;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeckPolicy
{
    use HandlesAuthorization;

    public function interact(User $user, Deck $deck)
    {
        if ($deck->user_id != $user->id && $deck->private) {
            // the Deck doesn't belong to the User & is private
            return false;
        }

        // the Deck belongs to the User or is public
        return true;
    }

    public function modify(User $user, Deck $deck)
    {
        if ($deck->user_id != $user->id) {
            // the Deck doesn't belong to the User
            return false;
        }

        // the Deck belongs to the User
        return true;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Deck  $deck
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Deck $deck)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Deck  $deck
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Deck $deck)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Deck  $deck
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Deck $deck)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Deck  $deck
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Deck $deck)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Deck  $deck
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Deck $deck)
    {
        //
    }
}
