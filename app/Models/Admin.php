<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = 'admins';
    protected $guard = 'admin';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function logs()
    {
        return $this->hasMany(AdminLog::class, 'admin_id');
    }

    public function getGaurdAttribute()
    {
        return 'admin';
    }

    /**
     * التحقق من وجود صلاحية معينة
     */
    public function hasPermission($permissionName)
    {
        if (!$this->role_id) {
            return false;
        }

        // تحميل العلاقات بدون استخدام eager loading لتجنب التكرار
        if (!$this->relationLoaded('role') || !$this->role->relationLoaded('permissions')) {
            $this->load(['role.permissions']);
        }

        if (!$this->role) {
            return false;
        }

        return $this->role->permissions
            ->contains('permission_name', $permissionName);
    }

    /**
     * التحقق من أن المستخدم له دور معين
     */
    public function hasRole($roleName)
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->role_name === $roleName;
    }

    /**
     * الحصول على جميع الصلاحيات
     */
    public function getAllPermissions()
    {
        if (!$this->role) {
            return collect();
        }

        if (!$this->role->relationLoaded('permissions')) {
            $this->role->load('permissions');
        }

        return $this->role->permissions;
    }

    /**
     * scope للبحث في المسؤولين
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%");
    }

    /**
     * scope للمسؤولين النشطين
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * scope للمسؤولين المعطلين
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
}