<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{

    protected $table = 'specialties';

    protected $fillable = [
        'name',
    ];
    protected $dates = ['created_at', 'updated_at'];
    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_specialty');
    }
}
