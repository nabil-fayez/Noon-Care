<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'message',
        'details',
        'file',
        'line',
        'url',
        'ip',
        'user_agent',
        'user_id'
    ];

    protected $casts = [
        'details' => 'array',
    ];

    /**
     * علاقة مع المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * scope للبحث في السجلات
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('message', 'like', "%{$search}%")
            ->orWhere('level', 'like', "%{$search}%")
            ->orWhere('file', 'like', "%{$search}%");
    }

    /**
     * scope للتصفية حسب المستوى
     */
    public function scopeLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * scope للتصفية حسب المستخدم
     */
    public function scopeOfUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}