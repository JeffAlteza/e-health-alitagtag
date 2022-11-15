<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'age',
        'date_of_consultation',
        'time_of_consultation',
        'nature_of_visit',
        'purpose_of_visit',
        'BP',
        'RR',
        'PR',
        'HC',
        'AC',
        'temp',
        'height',
        'weight',
        'LMP',
        'FHT',
        'EDC',
        'AOG',
        'FUNDIC_HT',
        'WAIST_CIR',
        'smoker',
        'alcohol_drinker',
        'chief_complaint',
        'recommendation',
    ];
}
