<?php

namespace App\Policies;

use App\Models\DoctorSchedule;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class DoctorSchedulePolicy
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
     * @param  \App\Models\DoctorSchedule  $doctorSchedule
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, DoctorSchedule $doctorSchedule)
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
        return auth()->user()->role_id != 4;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DoctorSchedule  $doctorSchedule
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, DoctorSchedule $doctorSchedule)
    {
        return Auth::user()->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DoctorSchedule  $doctorSchedule
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, DoctorSchedule $doctorSchedule)
    {
        return Auth::user()->isAdmin();
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteAny(User $user)
    {
        return Auth::user()->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DoctorSchedule  $doctorSchedule
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, DoctorSchedule $doctorSchedule)
    {
        return Auth::user()->isAdmin();
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDeleteAny(User $user)
    {
        return Auth::user()->isAdmin();
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DoctorSchedule  $doctorSchedule
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, DoctorSchedule $doctorSchedule)
    {
        return Auth::user()->isAdmin();
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restoreAny(User $user)
    {
        return Auth::user()->isAdmin();
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DoctorSchedule  $doctorSchedule
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function replicate(User $user, DoctorSchedule $doctorSchedule)
    {
        return Auth::user()->isAdmin();
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reorder(User $user)
    {
        return Auth::user()->isAdmin();
    }
}
