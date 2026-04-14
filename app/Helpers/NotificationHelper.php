<?php

namespace App\Helpers;

use App\Services\NotificationService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationHelper
{
    public static function sendWithFlash($type, $title, $message, $relatedId = null, $priority = 'medium')
    {
        if (Auth::check()) {
            $notificationService = app(NotificationService::class);
            $notificationService->send(
                Auth::id(),
                $type,
                $title,
                $message,
                $relatedId,
                $priority
            );
        }
    }

    public static function sendToAllRoles($type, $title, $message, $relatedId = null, $priority = 'medium')
    {
        $notificationService = app(NotificationService::class);
        $allUsers = User::all()->pluck('id_user')->toArray();
        $notificationService->sendToMultiple($allUsers, $type, $title, $message, $relatedId, $priority);
    }

    public static function sendToRole($role, $type, $title, $message, $relatedId = null, $priority = 'medium')
    {
        $notificationService = app(NotificationService::class);
        $users = User::where('role', $role)->pluck('id_user')->toArray();
        $notificationService->sendToMultiple($users, $type, $title, $message, $relatedId, $priority);
    }

    public static function sendToRoles($roles, $type, $title, $message, $relatedId = null, $priority = 'medium')
    {
        $notificationService = app(NotificationService::class);
        $users = User::whereIn('role', $roles)->pluck('id_user')->toArray();
        $notificationService->sendToMultiple($users, $type, $title, $message, $relatedId, $priority);
    }
}
