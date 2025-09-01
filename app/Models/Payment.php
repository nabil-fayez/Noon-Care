<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'appointment_id',
        'amount',
        'status',
        'payment_method',
        'transaction_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function getStatusTextAttribute(): string
    {
        return [
            'pending' => 'قيد الانتظار',
            'completed' => 'مكتمل',
            'failed' => 'فاشل',
            'refunded' => 'تم الاسترداد'
        ][$this->status] ?? $this->status;
    }
}
