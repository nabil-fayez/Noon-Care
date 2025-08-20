<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
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

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function logs()
    {
        return $this->hasMany(AdminLog::class, 'admin_id');
    }
}
