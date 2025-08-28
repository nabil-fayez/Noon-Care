<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSpecialty extends Model
{
    protected $table = 'doctor_specialty';

    protected $fillable = [
        'doctor_id',
        'specialty_id',
        'created_at',
        'updated_at'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
}
