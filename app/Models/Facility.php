<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Facility extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'facility';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'business_name',
        'address',
        'phone',
        'website',
        'description',
        'logo',
        'latitude',
        'longitude',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * العلاقة مع الأطباء (Many-to-Many)
     */
    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_facility')
            ->withPivot('status', 'available_for_appointments')
            ->withTimestamps();
    }

    /**
     * العلاقة مع الخدمات (Many-to-Many)
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'facility_services')
            ->withPivot('is_available')
            ->withTimestamps();
    }

    /**
     * العلاقة مع المواعيد
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * العلاقة مع السجلات الطبية
     */
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    /**
     * العلاقة مع تقييمات المنشأة
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * العلاقة مع أسعار الخدمات
     */
    public function servicePricing()
    {
        return $this->hasMany(FacilityServicePricing::class);
    }

    /**
     * الوصول إلى صورة الشعار (Accessor)
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            // تأكد من أن المسار صحيح
            return asset('storage/' . $this->logo);
        }

        return 'https://avatar.iran.liara.run/public/36';
    }

    /**
     * عدد الأطباء النشطين (Accessor)
     */
    public function getActiveDoctorsCountAttribute()
    {
        return $this->doctors()->wherePivot('status', 'active')->count();
    }

    /**
     * عدد الخدمات المتاحة (Accessor)
     */
    public function getAvailableServicesCountAttribute()
    {
        return $this->services()->wherePivot('is_available', true)->count();
    }

    /**
     * نطاق الاستعلام للمنشآت النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * نطاق الاستعلام للبحث بالاسم أو العنوان
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('business_name', 'like', "%{$search}%")
            ->orWhere('address', 'like', "%{$search}%");
    }

    public function getGaurdAttribute()
    {
        return 'facility';
    }
}
