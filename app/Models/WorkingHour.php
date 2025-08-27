<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingHour extends Model
{
    protected $table = 'working_hours';

    protected $fillable = [
        'doctor_id',
        'facility_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];
    protected $dates = ['created_at', 'updated_at'];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}