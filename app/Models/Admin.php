<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    protected $dates = ['created_at', 'updated_at'];

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

        // استخدام eager loading لتحميل العلاقات
        $adminWithPermissions = self::with(['role.permissions'])
            ->where('id', $this->id)
            ->first();

        if (!$adminWithPermissions->role) {
            return false;
        }

        return $adminWithPermissions->role->permissions
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

        return $this->role->permissions;
    }
}