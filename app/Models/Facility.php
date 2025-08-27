<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
use SoftDeletes;

    protected $table = 'facilities';

    protected $fillable = [
        'username',
        'business_name',
        'address',
        'phone',
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_facility')
            ->withPivot('consultation_price');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function workingHours()
    {
        return $this->hasMany(WorkingHour::class);
    }
}
