<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'permission_name',
        'description',
        'module',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id')
            ->withTimestamps();
    }


    /**
     * الحصول على جميع الأدوار التي لها هذه الصلاحية
     */
    public function getRolesWithPermission()
    {
        return $this->roles;
    }

    /**
     * التجميع حسب الوحدة النمطية
     */
    public function scopeByModule($query, $module)
    {
        return $query->where('module', $module);
    }
}
