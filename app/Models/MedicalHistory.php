<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'condition',
        'description',
        'diagnosed_date',
        'treatment',
        'medications',
        'allergies',
        'family_history'
    ];

    protected $casts = [
        'diagnosed_date' => 'date',
        'medications' => 'array',
        'allergies' => 'array',
        'family_history' => 'array'
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
