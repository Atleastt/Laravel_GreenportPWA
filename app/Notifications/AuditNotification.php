<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuditNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $auditId;
    protected $type;

    public function __construct($message, $auditId = null, $type = 'info')
    {
        $this->message = $message;
        $this->auditId = $auditId;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Notifikasi Audit - Greenport')
                    ->greeting('Halo ' . $notifiable->name)
                    ->line($this->message)
                    ->action('Lihat Detail', url('/dashboard'))
                    ->line('Terima kasih telah menggunakan sistem audit Greenport!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'audit_id' => $this->auditId,
            'type' => $this->type,
            'timestamp' => now()->toISOString(),
        ];
    }
}
