<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Doctor extends Model
{
    use HasFactory;

    protected $guard = 'doctor';

    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'bio',
        'profile_image',
        'is_verified'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'is_verified' => 'boolean'
    ];

    /**
     * العلاقة مع التخصصات
     */
    public function specialties(): BelongsToMany
    {
        return $this->belongsToMany(Specialty::class, 'doctor_specialty');
    }

    /**
     * العلاقة مع المنشآت
     */
    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'doctor_facility')
            ->withPivot('status', 'available_for_appointments');
    }
    /**
     * العلاقة مع التقييمات
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * السمات المحسوبة - متوسط التقييم
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->reviews->avg('rating') ?? 0;
    }


    /**
     * العلاقة مع المواعيد
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * العلاقة مع أوقات العمل
     */
    public function workingHours(): HasMany
    {
        return $this->hasMany(WorkingHour::class);
    }

    /**
     * السمات المحسوبة
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
    /**
     * السمات المحسوبة - رابط الصورة
     */
    public function getProfileImageUrlAttribute(): ?string
    {
        if (!$this->profile_image) {
            return null;
        }
        return asset('storage/' . $this->profile_image);
    }
    /**
     * النطاقات (Scopes)
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeWithSpecialty($query, $specialtyId)
    {
        return $query->whereHas('specialties', function ($q) use ($specialtyId) {
            $q->where('specialties.id', $specialtyId);
        });
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('first_name', 'like', "%$searchTerm%")
                ->orWhere('last_name', 'like', "%$searchTerm%")
                ->orWhere('username', 'like', "%$searchTerm%")
                ->orWhere('email', 'like', "%$searchTerm%");
        });
    }

    public function appointmentsCount(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function reviewsCount(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function facilitiesCount(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'doctor_facility');
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }
    public function getGaurdAttribute()
    {
        return 'doctor';
    }
}