<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityService extends Model
{
    protected $table = 'facility_services';

    protected $fillable = [
        'facility_id',
        'service_id',
        'is_available',
    ];
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function services()
    {
        return $this->belongsTo(Service::class);
    }
}
