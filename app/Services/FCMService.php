<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FCMService
{
    protected $fcmUrl = 'https://fcm.googleapis.com/v1/projects/lms-ai-61e4f/messages:send';
    protected $credentialsPath;

    public function __construct()
    {
        $this->credentialsPath = storage_path('app/firebase/service-account.json');
    }

    protected function getAccessToken(): ?string
    {
        if (!file_exists($this->credentialsPath)) {
            return null;
        }

        try {
            $serviceAccount = json_decode(file_get_contents($this->credentialsPath), true);
            $credentialsClass = '\Google\Auth\Credentials\ServiceAccountCredentials';
            $credentials = new $credentialsClass(
                'https://www.googleapis.com/auth/firebase.messaging',
                $serviceAccount
            );
            $token = $credentials->fetchAuthToken();
            return $token['access_token'] ?? null;
        } catch (\Exception $e) {
            Log::error('FCM Auth Error: ' . $e->getMessage());
            return null;
        }
    }

    public function sendNotification($token, $title, $body, $data = [])
    {
        if (!$token) {
            return false;
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            Log::warning('FCM: No access token available');
            return false;
        }

        // Add unique notification ID to prevent duplicates
        $data['notification_id'] = $data['notification_id'] ?? uniqid('notif_', true);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->fcmUrl, [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $data,
                    'webpush' => [
                        'notification' => [
                            'icon' => '/images/LMS.png',
                            'tag' => $data['notification_id'],
                            'renotify' => false,
                        ],
                    ],
                ],
            ]);

            if ($response->successful()) {
                Log::info('FCM notification sent successfully');
                return true;
            } else {
                Log::error('FCM Error: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('FCM Error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendMultipleNotifications($tokens, $title, $body, $data = [])
    {
        $success = 0;
        foreach ($tokens as $token) {
            if ($this->sendNotification($token, $title, $body, $data)) {
                $success++;
            }
        }
        return $success;
    }
}
