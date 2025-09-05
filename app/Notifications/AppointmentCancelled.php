<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class AppointmentCancelled extends Notification implements ShouldQueue
{
    use Queueable;

    public $appointment;
    public $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment, $reason = null)
    {
        $this->appointment = $appointment;
        $this->reason = $reason;
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
        $mailMessage = (new MailMessage)
            ->subject('تم إلغاء حجزك - Noon Care')
            ->greeting('مرحباً ' . $notifiable->first_name)
            ->line('نود إعلامك أنه تم إلغاء حجزك. تفاصيل الحجز الملغي:')
            ->line(new HtmlString('<strong>الطبيب:</strong> ' . $this->appointment->doctor->full_name))
            ->line(new HtmlString('<strong>التاريخ والوقت:</strong> ' . $this->appointment->appointment_datetime->format('Y-m-d H:i')))
            ->line(new HtmlString('<strong>المكان:</strong> ' . $this->appointment->facility->business_name))
            ->line(new HtmlString('<strong>نوع الخدمة:</strong> ' . $this->appointment->service->name));

        if ($this->reason) {
            $mailMessage->line(new HtmlString('<strong>سبب الإلغاء:</strong> ' . $this->reason));
        }

        $mailMessage->action('عرض التفاصيل', url('/appointments/' . $this->appointment->id))
            ->line('نأسف للإزعاج ونتطلع لرؤيتك في زيارة أخرى.');

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $message = 'تم إلغاء حجزك مع الدكتور ' . $this->appointment->doctor->full_name .
            ' في ' . $this->appointment->appointment_datetime->format('Y-m-d H:i');

        if ($this->reason) {
            $message .= '. سبب الإلغاء: ' . $this->reason;
        }

        return [
            'appointment_id' => $this->appointment->id,
            'type' => 'appointment_cancelled',
            'title' => 'تم إلغاء حجزك',
            'message' => $message,
            'doctor_name' => $this->appointment->doctor->full_name,
            'appointment_date' => $this->appointment->appointment_datetime->format('Y-m-d'),
            'appointment_time' => $this->appointment->appointment_datetime->format('H:i'),
            'facility_name' => $this->appointment->facility->business_name,
            'cancellation_reason' => $this->reason,
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