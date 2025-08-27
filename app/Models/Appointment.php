<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;
    protected $table = 'appointments';

    protected $fillable = [
        'doctor_id',
        'facility_id',
        'doctor_facility_id',
        'patient_id',
        'appointment_datetime',
        'status',
    ];
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
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