<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'name',
        'gender',
        'birthday',
        'phone_number',
        'category',
        'specification',
        'date',
        'status'

    ];

    public function user():BelongsTo
    {
        return $this->BelongsTo(related:User::class);
    }

    public function doctor():BelongsTo
    {
        return $this->BelongsTo(related:User::class);
    }
}
