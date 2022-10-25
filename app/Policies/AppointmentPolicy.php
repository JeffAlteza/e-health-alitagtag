<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
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
        return auth()->user();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Appointment $appointment)
    {
        return auth()->user();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return auth()->user();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Appointment $appointment)
    {
        // return auth()->user();
        return auth()->user();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Appointment $appointment)
    {
        return auth()->user();
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteAny(User $user)
    {
        return auth()->user();
    }

}
