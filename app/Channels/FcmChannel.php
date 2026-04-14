<?php

namespace App\Channels;

use App\Services\FCMService;
use Illuminate\Notifications\Notification;

class FcmChannel
{
    protected $fcmService;

    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function send($notifiable, Notification $notification)
    {
        if (!$notifiable->fcm_token) {
            return;
        }

        $data = $notification->toFcm($notifiable);
        
        $this->fcmService->sendNotification(
            $data['token'],
            $data['notification']['title'],
            $data['notification']['body'],
            $data['data'] ?? []
        );
    }
}
