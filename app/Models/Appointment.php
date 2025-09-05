<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory;

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

    // العلاقة مع الطبيب
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    // العلاقة مع المريض
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    // العلاقة مع المنشأة
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    // العلاقة مع الخدمة
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    // العلاقة مع شركة التأمين
    public function insuranceCompany(): BelongsTo
    {
        return $this->belongsTo(InsuranceCompany::class, 'insurance_company_id');
    }

    // العلاقة مع الطبيب في المنشأة (DoctorFacility)
    public function doctorFacility(): BelongsTo
    {
        return $this->belongsTo(DoctorFacility::class, 'doctor_facility_id');
    }

    // العلاقة مع المراجعة
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    // العلاقة مع الدفع
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    // العلاقة مع السجلات الطبية
    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
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

    // Accessor for date only
    public function getDateAttribute()
    {
        return $this->appointment_datetime->format('Y-m-d');
    }

    // Accessor for time only
    public function getTimeAttribute()
    {
        return $this->appointment_datetime->format('H:i');
    }
}
