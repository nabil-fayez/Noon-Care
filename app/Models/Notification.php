<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'user_type',
        'title',
        'message',
        'is_read',
    ];

    /**
     * الحقول التي يجب إخفاؤها.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at'
    ];

    /**
     * الحقول التي يجب تحويلها إلى أنواع محددة.
     *
     * @var array
     */
    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime'
    ];

    /**
     * الجدول المرتبط بالنموذج.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * العلاقة مع المستخدم (متعددة الأشكال).
     */
    public function user(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'user_type', 'user_id');
    }

    /**
     * نطاق الاستعلام للإشعارات غير المقروءة.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * نطاق الاستعلام للإشعارات المقروءة.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * نطاق الاستعلام للإشعارات حسب نوع المستخدم.
     */
    public function scopeForUserType($query, $userType)
    {
        return $query->where('user_type', $userType);
    }

    /**
     * نطاق الاستعلام للإشعارات حسب معرّف المستخدم.
     */
    public function scopeForUser($query, $userId, $userType)
    {
        return $query->where('user_id', $userId)
            ->where('user_type', $userType);
    }

    /**
     * نطاق الاستعلام للإشعارات الحديثة.
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * وسم الإشعار كمقروء.
     */
    public function markAsRead(): bool
    {
        return $this->update(['is_read' => true]);
    }

    /**
     * وسم الإشعار كغير مقروء.
     */
    public function markAsUnread(): bool
    {
        return $this->update(['is_read' => false]);
    }

    /**
     * السمات المحسوبة - رابط الإشعار.
     */
    public function getLinkAttribute(): ?string
    {
        return $this->data['link'] ?? null;
    }

    /**
     * السمات المحسوبة - أيقونة الإشعار.
     */
    public function getIconAttribute(): string
    {
        return $this->data['icon'] ?? 'bi-bell';
    }

    /**
     * السمات المحسوبة - نوع الإشعار.
     */
    public function getTypeAttribute(): string
    {
        return $this->data['type'] ?? 'info';
    }

    /**
     * السمات المحسوبة - وقت الإشعار بصيغة سهلة.
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * السمات المحسوبة - فئة CSS بناءً على نوع الإشعار.
     */
    public function getCssClassAttribute(): string
    {
        $types = [
            'success' => 'text-bg-success',
            'warning' => 'text-bg-warning',
            'error' => 'text-bg-danger',
            'info' => 'text-bg-info',
            'primary' => 'text-bg-primary'
        ];

        return $types[$this->type] ?? $types['info'];
    }

    /**
     * السمات المحسوبة - فئة أيقونة بناءً على نوع الإشعار.
     */
    public function getIconClassAttribute(): string
    {
        $icons = [
            'success' => 'bi-check-circle',
            'warning' => 'bi-exclamation-triangle',
            'error' => 'bi-x-circle',
            'info' => 'bi-info-circle',
            'primary' => 'bi-bell'
        ];

        return $icons[$this->type] ?? $icons['info'];
    }

    /**
     * إنشاء إشعار جديد.
     */
    public static function createNotification(array $data): self
    {
        return static::create([
            'user_id' => $data['user_id'],
            'user_type' => $data['user_type'],
            'title' => $data['title'],
            'message' => $data['message'],
            'is_read' => $data['is_read'] ?? false,
        ]);
    }

    /**
     * إرسال إشعارات مجمعة.
     */
    public static function sendBulkNotifications(array $notifications): void
    {
        foreach ($notifications as $notification) {
            static::createNotification($notification);
        }
    }

    /**
     * علامة على أن النموذج لا يجب أن يكون مؤقتًا (لا يستخدم Soft Deletes).
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * الأعمدة التي يجب تعيين قيمها الافتراضية.
     *
     * @var array
     */
    protected $attributes = [
        'is_read' => false,
    ];
}