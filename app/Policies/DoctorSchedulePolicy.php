<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DoctorSchedule;
use Illuminate\Auth\Access\HandlesAuthorization;

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
        return $user->can('view_any_doctor::schedule');
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
        return $user->can('view_doctor::schedule');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create_doctor::schedule');
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
        return $user->can('update_doctor::schedule');
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
        return $user->can('delete_doctor::schedule');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteAny(User $user)
    {
        return $user->can('delete_any_doctor::schedule');
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
        return $user->can('force_delete_doctor::schedule');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDeleteAny(User $user)
    {
        return $user->can('force_delete_any_doctor::schedule');
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
        return $user->can('restore_doctor::schedule');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restoreAny(User $user)
    {
        return $user->can('restore_any_doctor::schedule');
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
        return $user->can('replicate_doctor::schedule');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reorder(User $user)
    {
        return $user->can('reorder_doctor::schedule');
    }

}
