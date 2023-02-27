<?php

namespace Domain\Doctor\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'date',
        'time_start',
        'time_end',
        'doctor_id',
        'status',
    ];

    public function doctor(): BelongsTo
    {
        return $this->BelongsTo(related:User::class);
    }
}
