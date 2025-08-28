<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use SoftDeletes;

    protected $table = 'doctors';

    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'doctor_specialty');
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'doctor_facility')
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
