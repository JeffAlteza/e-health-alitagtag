<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JeffGreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable as two_FA;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, two_FA;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role_id',
        'password',
        'address',
        'category',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function roles()
    // {
    //     return $this->hasMany(Roles::class);
    // }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Roles::class);
    }

    public function appointments_user()
    {
        return $this->hasMany(Appointment::class);
    }

    public function appointments_doctor()
    {
        return $this->hasMany(Appointment::class);
    }

    public function doctorschedule()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function canAccessFilament(): bool
    {
        return true;
    }
}
