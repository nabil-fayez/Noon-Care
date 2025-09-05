<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
        use HasFactory;

    protected $table = 'roles';

    protected $primaryKey = 'id';

    protected $fillable = [
        'role_name',
        'description',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function admins()
    {
        return $this->hasMany(Admin::class, 'role_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    /**
     * إضافة صلاحية للدور
     */
    public function assignPermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('permission_name', $permission)->first();
        }

        if ($permission) {
            return $this->permissions()->attach($permission);
        }

        return false;
    }

    /**
     * إزالة صلاحية من الدور
     */
    public function removePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('permission_name', $permission)->first();
        }

        if ($permission) {
            return $this->permissions()->detach($permission);
        }

        return false;
    }

    /**
     * مزامنة الصلاحيات للدور
     */
    public function syncPermissions(array $permissions)
    {
        $permissionIds = [];

        foreach ($permissions as $permission) {
            if (is_string($permission)) {
                $permissionModel = Permission::where('permission_name', $permission)->first();
                if ($permissionModel) {
                    $permissionIds[] = $permissionModel->id;
                }
            } else {
                $permissionIds[] = $permission;
            }
        }

        return $this->permissions()->sync($permissionIds);
    }
}
