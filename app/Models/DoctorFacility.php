<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorFacility extends Model
{
    use HasFactory;

    protected $table = 'doctor_facility';

    protected $fillable = [
        'doctor_id',
        'facility_id',
        'consultation_price',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}