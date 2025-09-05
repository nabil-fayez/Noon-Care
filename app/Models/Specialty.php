<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Specialty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'color',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * العلاقة مع الأطباء
     */
    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(Doctor::class, 'doctor_specialty');
    }

    /**
     * نطاق الاستعلام للتخصصات النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * نطاق الاستعلام للتخصصات حسب البحث
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('name', 'like', "%$searchTerm%")
            ->orWhere('description', 'like', "%$searchTerm%");
    }

    /**
     * السمات المحسوبة - رابط الأيقونة
     */
    public function getIconUrlAttribute(): ?string
    {
        if (!$this->icon) {
            return null;
        }

        return asset('storage/' . $this->icon);
    }
    /**
     * السمات المحسوبة - عدد الأطباء
     */
    public function getDoctorsCountAttribute(): int
    {
        return $this->doctors()->count();
    }

    /**
     * السمات المحسوبة - التخصص مع عدد الأطباء
     */
    public function getNameWithCountAttribute(): string
    {
        return "{$this->name} ({$this->doctors_count})";
    }

    /**
     * تفعيل التخصص
     */
    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * تعطيل التخصص
     */
    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }

    /**
     * التحقق مما إذا كان التخصص قيد الاستخدام
     */
    public function isInUse(): bool
    {
        return $this->doctors_count > 0;
    }
}
