<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\GeneralNotification;

class NotificationService
{
    public function sendNotification($userId, $userType, $title, $message, $sendEmail = false): Notification
    {
        $notification = Notification::create([
            'user_id' => $userId,
            'user_type' => $userType,
            'title' => $title,
            'message' => $message
        ]);

        // إرسال بريد إلكتروني إذا طُلب
        if ($sendEmail) {
            $user = $this->getUser($userId, $userType);
            if ($user && $user->email) {
                Mail::to($user->email)->send(new GeneralNotification($title, $message));
            }
        }

        return $notification;
    }

    public function sendBulkNotification($userIds, $userType, $title, $message): void
    {
        foreach ($userIds as $userId) {
            $this->sendNotification($userId, $userType, $title, $message);
        }
    }

    private function getUser($userId, $userType)
    {
        return match ($userType) {
            'patient' => \App\Models\Patient::find($userId),
            'doctor' => \App\Models\Doctor::find($userId),
            'facility' => \App\Models\Facility::find($userId),
            'admin' => \App\Models\Admin::find($userId),
            default => null,
        };
    }

    public function markAsRead($notificationId): void
    {
        Notification::where('id', $notificationId)->update(['is_read' => true]);
    }

    public function markAllAsRead($userId, $userType): void
    {
        Notification::where('user_id', $userId)
            ->where('user_type', $userType)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
}
