<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InsuranceCompany extends Model
{
    protected $fillable = ['name', 'contact_email', 'contact_phone', 'address'];

    public function facilityServicePricings(): HasMany
    {
        return $this->hasMany(FacilityServicePricing::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
