<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Patient extends Authenticatable
{
    use HasFactory;
    protected $guard = 'patient';

    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'date_of_birth',
        'gender',
        'profile_image',
        'address',
        'emergency_contact',
        'blood_type',
        'allergies',
        'chronic_diseases',
        'is_active'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'allergies' => 'array',
        'chronic_diseases' => 'array'
    ];

    /**
     * العلاقة مع المواعيد
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * العلاقة مع التقييمات
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * العلاقة مع الوصفات الطبية
     */
    // public function prescriptions(): HasMany
    // {
    //     return $this->hasMany(Prescription::class);
    // }

    /**
     * العلاقة مع السجلات الطبية
     */
    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }

    /**
     * العلاقة مع الفواتير
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * نطاق الاستعلام للمرضى النشطين
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * نطاق الاستعلام للبحث بالاسم أو البريد
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('first_name', 'like', "%$searchTerm%")
            ->orWhere('last_name', 'like', "%$searchTerm%")
            ->orWhere('username', 'like', "%$searchTerm%")
            ->orWhere('email', 'like', "%$searchTerm%");
    }

    /**
     * السمات المحسوبة - الاسم الكامل
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * السمات المحسوبة - العمر
     */
    public function getAgeAttribute(): int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : 0;
    }

    /**
     * السمات المحسوبة - رابط صورة الملف الشخصي
     */
    public function getProfileImageUrlAttribute(): ?string
    {
        if (!$this->profile_image) {
            return null;
        }

        return asset('storage/' . $this->profile_image);
    }

    /**
     * السمات المحسوبة - عدد المواعيد القادمة
     */
    public function getUpcomingAppointmentsCountAttribute(): int
    {
        return $this->appointments()
            ->where('status', 'confirmed')
            ->where('appointment_datetime', '>', now())
            ->count();
    }

    /**
     * السمات المحسوبة - عدد المواعيد المنتهية
     */
    public function getCompletedAppointmentsCountAttribute(): int
    {
        return $this->appointments()
            ->where('status', 'done')
            ->count();
    }

    /**
     * تفعيل حساب المريض
     */
    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * تعطيل حساب المريض
     */
    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }

    /**
     * التحقق من وجود سجلات طبية
     */
    public function hasMedicalRecords(): bool
    {
        return $this->medicalRecords()->exists();
    }
    public function getGaurdAttribute()
    {
        return 'patient';
    }

    public function completed_appointments()
    {
        return Appointment::where('status', '=', 'done',)->where('patient_id', '=', $this->id)->get();
    }
}