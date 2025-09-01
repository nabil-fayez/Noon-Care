<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    protected $fillable = [
        'doctor_id',
        'facility_id',
        'doctor_facility_id',
        'patient_id',
        'service_id',
        'insurance_company_id',
        'appointment_datetime',
        'duration',
        'status',
        'notes',
        'cancellation_reason',
        'price'
    ];

    protected $casts = [
        'appointment_datetime' => 'datetime',
        'price' => 'decimal:2'
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function insuranceCompany(): BelongsTo
    {
        return $this->belongsTo(InsuranceCompany::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    // Accessor for status text
    public function getStatusTextAttribute(): string
    {
        return [
            'new' => 'جديد',
            'confirmed' => 'مؤكد',
            'cancelled' => 'ملغي',
            'done' => 'منتهي'
        ][$this->status] ?? $this->status;
    }

    // Accessor for status color
    public function getStatusColorAttribute(): string
    {
        return [
            'new' => 'warning',
            'confirmed' => 'success',
            'cancelled' => 'danger',
            'done' => 'info'
        ][$this->status] ?? 'secondary';
    }
}