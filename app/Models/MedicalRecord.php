<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_id',
        'facility_id',
        'record_type',
        'title',
        'description',
        'diagnosis',
        'symptoms',
        'treatment_plan',
        'prescription',
        'test_results',
        'notes',
        'record_date',
        'follow_up_date',
        'attachment_path',
        'attachment_name',
        'status',
        'is_urgent',
        'requires_follow_up'
    ];

    protected $casts = [
        'record_date' => 'date',
        'follow_up_date' => 'date',
        'is_urgent' => 'boolean',
        'requires_follow_up' => 'boolean',
    ];

    /**
     * العلاقة مع المريض
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * العلاقة مع الطبيب
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * العلاقة مع الموعد
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * العلاقة مع المنشأة
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * نطاق الاستعلام للسجلات النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * نطاق الاستعلام للسجلات العاجلة
     */
    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    /**
     * نطاق الاستعلام للسجلات التي تتطلب متابعة
     */
    public function scopeRequiresFollowUp($query)
    {
        return $query->where('requires_follow_up', true);
    }

    /**
     * نطاق الاستعلام للسجلات حسب النوع
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('record_type', $type);
    }

    /**
     * السمات المحسوبة - وجود مرفق
     */
    public function getHasAttachmentAttribute(): bool
    {
        return !empty($this->attachment_path);
    }

    /**
     * السمات المحسوبة - رابط المرفق
     */
    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment_path ? asset('storage/' . $this->attachment_path) : null;
    }

    /**
     * السمات المحسوبة - يحتاج متابعة
     */
    public function getNeedsFollowUpAttribute(): bool
    {
        return $this->requires_follow_up && $this->follow_up_date >= now();
    }

    /**
     * تحديث حالة السجل
     */
    public function updateStatus(string $status): bool
    {
        return $this->update(['status' => $status]);
    }


    public function markAsRequiresFollowUp($followUpDate): bool
    {
        return $this->update([
            'requires_follow_up' => true,
            'follow_up_date' => $followUpDate
        ]);
    }
}