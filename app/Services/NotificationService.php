<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification as NotificationModel;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $fcmService;

    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function send($userId, $type, $title, $message, $relatedId = null, $priority = 'medium')
    {
        try {
            $user = User::find($userId);
            if (!$user) return false;

            // Customize message based on role
            $customMessage = $this->customizeMessageForRole($user->role, $type, $message, $relatedId);
            $customTitle = $this->customizeTitleForRole($user->role, $type, $title);

            // Simpan ke database
            NotificationModel::create([
                'id_user' => $userId,
                'type' => $type,
                'priority' => $priority,
                'title' => $customTitle,
                'message' => $customMessage,
                'related_id' => $relatedId,
                'is_read' => false,
            ]);

            // Kirim email & push notification
            $actionUrl = $this->getActionUrl($user->role, $type, $relatedId);
            $user->notify(new SystemNotification($customTitle, $customMessage, $actionUrl));

            return true;
        } catch (\Exception $e) {
            Log::error('Notification Error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendToMultiple($userIds, $type, $title, $message, $relatedId = null, $priority = 'medium')
    {
        foreach ($userIds as $userId) {
            $this->send($userId, $type, $title, $message, $relatedId, $priority);
        }
    }

    protected function customizeTitleForRole($role, $type, $title)
    {
        switch ($role) {
            case 'admin':
                return "[ADMIN] " . $title;
            case 'guru':
                return "[GURU] " . $title;
            case 'siswa':
                return "[SISWA] " . $title;
            default:
                return $title;
        }
    }

    protected function customizeMessageForRole($role, $type, $message, $relatedId)
    {
        switch ($role) {
            case 'admin':
                if ($type === 'class_created') {
                    return $message . " - Anda dapat memonitor kelas ini di dashboard admin.";
                }
                if ($type === 'new_assignment') {
                    return $message . " - Anda dapat melihat detail tugas di dashboard admin.";
                }
                break;
            case 'guru':
                if ($type === 'class_created') {
                    return $message . " - Anda dapat berkolaborasi dengan guru lain.";
                }
                if ($type === 'new_assignment') {
                    return $message . " - Anda dapat melihat progress siswa.";
                }
                break;
            case 'siswa':
                if ($type === 'class_created') {
                    return $message . " - Anda dapat bergabung dengan kelas ini.";
                }
                if ($type === 'new_assignment') {
                    return $message . " - Segera kerjakan tugas ini!";
                }
                break;
        }
        return $message;
    }

    protected function getActionUrl($role, $type, $relatedId)
    {
        if (!$relatedId) {
            return url('/dashboard');
        }

        try {
            switch ($type) {
                case 'new_assignment':
                    if ($role === 'siswa') {
                        return route('siswa.assignments.show', $relatedId);
                    }
                    return url('/dashboard');
                case 'new_material':
                    return url('/dashboard');
                case 'feedback':
                case 'grade':
                    if ($role === 'siswa') {
                        return route('siswa.submissions.show', $relatedId);
                    }
                    return url('/dashboard');
                case 'class_created':
                    if ($role === 'admin') {
                        return url('/admin/classes');
                    } elseif ($role === 'guru') {
                        return url('/guru/classes');
                    } elseif ($role === 'siswa') {
                        return url('/siswa/classes');
                    }
                    return url('/dashboard');
                default:
                    return url('/dashboard');
            }
        } catch (\Exception $e) {
            return url('/dashboard');
        }
    }
}
