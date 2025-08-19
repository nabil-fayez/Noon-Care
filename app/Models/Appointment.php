<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';

    protected $fillable = [
        'doctor_id',
        'facility_id',
        'doctor_facility_id',
        'patient_id',
        'appointment_datetime',
        'status',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function doctorFacility()
    {
        return $this->belongsTo(DoctorFacility::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
