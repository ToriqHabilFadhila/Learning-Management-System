<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\FcmChannel;

class SystemNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $title;
    public $message;
    public $actionUrl;
    public $actionText;

    public function __construct($title, $message, $actionUrl = null, $actionText = 'Lihat Detail')
    {
        $this->title = $title;
        $this->message = $message;
        $this->actionUrl = $actionUrl;
        $this->actionText = $actionText;
    }

    public function via(object $notifiable): array
    {
        $channels = ['mail', 'database'];
        
        // Tambahkan FCM jika user punya token
        if ($notifiable->fcm_token) {
            $channels[] = FcmChannel::class;
        }
        
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->title)
            ->greeting('Halo, ' . $notifiable->nama . '!')
            ->line($this->message);
        
        if ($this->actionUrl) {
            $mail->action($this->actionText, $this->actionUrl);
        }
        
        return $mail->line('Terima kasih telah menggunakan Learning Management System.');
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'token' => $notifiable->fcm_token,
            'notification' => [
                'title' => $this->title,
                'body' => $this->message,
            ],
            'data' => [
                'url' => $this->actionUrl ?? '',
            ],
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'action_url' => $this->actionUrl,
        ];
    }
}
