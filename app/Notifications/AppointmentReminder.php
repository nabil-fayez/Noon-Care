<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class AppointmentReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('تذكير بموعدك - Noon Care')
            ->greeting('مرحباً ' . $notifiable->first_name)
            ->line('هذا تذكير بموعدك القادم. تفاصيل الحجز:')
            ->line(new HtmlString('<strong>الطبيب:</strong> ' . $this->appointment->doctor->full_name))
            ->line(new HtmlString('<strong>التاريخ والوقت:</strong> ' . $this->appointment->appointment_datetime->format('Y-m-d H:i')))
            ->line(new HtmlString('<strong>المكان:</strong> ' . $this->appointment->facility->business_name))
            ->line(new HtmlString('<strong>نوع الخدمة:</strong> ' . $this->appointment->service->name))
            ->action('عرض التفاصيل', url('/appointments/' . $this->appointment->id))
            ->line('نرجو الحضور قبل الموعد بـ 15 دقيقة.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'type' => 'appointment_reminder',
            'title' => 'تذكير بموعدك',
            'message' => 'موعدك مع الدكتور ' . $this->appointment->doctor->full_name .
                ' في ' . $this->appointment->appointment_datetime->format('Y-m-d H:i'),
            'doctor_name' => $this->appointment->doctor->full_name,
            'appointment_date' => $this->appointment->appointment_datetime->format('Y-m-d'),
            'appointment_time' => $this->appointment->appointment_datetime->format('H:i'),
            'facility_name' => $this->appointment->facility->business_name,
            'url' => '/appointments/' . $this->appointment->id,
        ];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }
}
