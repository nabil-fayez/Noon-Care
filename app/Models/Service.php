<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'category'];

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'facility_services')
            ->withPivot('is_available');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function facilityServicePricings(): HasMany
    {
        return $this->hasMany(FacilityServicePricing::class);
    }
}