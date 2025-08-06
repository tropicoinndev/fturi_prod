<?php

namespace App\Policies;

use App\Models\User;
use App\Models\departamento;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartamentosPolicy
{
    use HandlesAuthorization;

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
     * @param  \App\Models\departamentos  $departamentos
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, departamentos $departamentos)
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
     * @param  \App\Models\departamento  $departamento
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, departamento $departamento)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\departamento  $departamento
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, departamento $departamento)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\departamento  $departamento
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, departamento $departamento)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\departamento  $departamento
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, departamento $departamento)
    {
        //
    }
}
