<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminNotificationService
{
    /**
     * Notify admin when new user registers
     */
    public static function notifyUserRegistered($user)
    {
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'id_user' => $admin->id_user,
                'type' => 'user_registered',
                'priority' => 'high',
                'title' => 'User Baru Terdaftar',
                'message' => "User baru '{$user->nama}' ({$user->email}) telah mendaftar sebagai {$user->role}. Perlu approval untuk aktivasi akun.",
                'related_id' => $user->id_user,
                'created_at' => now(),
            ]);
        }
    }

    /**
     * Notify admin when guru creates new class
     */
    public static function notifyClassCreated($class, $guru)
    {
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'id_user' => $admin->id_user,
                'type' => 'class_created',
                'priority' => 'medium',
                'title' => 'Kelas Baru Dibuat',
                'message' => "Guru '{$guru->nama}' telah membuat kelas baru '{$class->nama_kelas}' - {$class->deskripsi}.",
                'related_id' => $class->id_class,
                'created_at' => now(),
            ]);
        }
    }

    /**
     * Notify admin about system errors
     */
    public static function notifySystemError($errorMessage, $context = [])
    {
        $admins = User::where('role', 'admin')->get();
        
        $contextStr = !empty($context) ? ' | Context: ' . json_encode($context) : '';
        
        foreach ($admins as $admin) {
            Notification::create([
                'id_user' => $admin->id_user,
                'type' => 'system_error',
                'priority' => 'high',
                'title' => 'Error Sistem Terdeteksi',
                'message' => "Error: {$errorMessage}{$contextStr}",
                'related_id' => null,
                'created_at' => now(),
            ]);
        }
    }

    /**
     * Send daily statistics to admin
     */
    public static function sendDailyStats()
    {
        $admins = User::where('role', 'admin')->get();
        
        // Get today's stats
        $today = now()->startOfDay();
        $newUsers = User::where('created_at', '>=', $today)->count();
        $newClasses = \App\Models\Classes::where('created_at', '>=', $today)->count();
        $newAssignments = \App\Models\Assignment::where('created_at', '>=', $today)->count();
        $submissions = \App\Models\Submission::where('submitted_at', '>=', $today)->count();
        $activeUsers = User::where('last_login', '>=', $today)->count();
        
        $message = "📊 Statistik Hari Ini:\n";
        $message .= "👥 User Baru: {$newUsers}\n";
        $message .= "🏫 Kelas Baru: {$newClasses}\n";
        $message .= "📝 Tugas Baru: {$newAssignments}\n";
        $message .= "✅ Pengumpulan: {$submissions}\n";
        $message .= "🟢 User Aktif: {$activeUsers}";
        
        foreach ($admins as $admin) {
            Notification::create([
                'id_user' => $admin->id_user,
                'type' => 'daily_stats',
                'priority' => 'low',
                'title' => 'Laporan Harian Sistem',
                'message' => $message,
                'related_id' => null,
                'created_at' => now(),
            ]);
        }
    }

    /**
     * Check storage and notify if running low
     */
    public static function checkStorageWarning()
    {
        $admins = User::where('role', 'admin')->get();
        
        // Get storage info
        $publicPath = storage_path('app/public');
        $totalSpace = disk_total_space($publicPath);
        $freeSpace = disk_free_space($publicPath);
        $usedSpace = $totalSpace - $freeSpace;
        $usedPercentage = ($usedSpace / $totalSpace) * 100;
        
        // Notify if storage > 80%
        if ($usedPercentage > 80) {
            $usedGB = round($usedSpace / 1024 / 1024 / 1024, 2);
            $totalGB = round($totalSpace / 1024 / 1024 / 1024, 2);
            $freeGB = round($freeSpace / 1024 / 1024 / 1024, 2);
            
            $message = "⚠️ Storage hampir penuh!\n";
            $message .= "Terpakai: {$usedGB}GB / {$totalGB}GB (" . round($usedPercentage, 1) . "%)\n";
            $message .= "Tersisa: {$freeGB}GB\n";
            $message .= "Segera lakukan cleanup atau upgrade storage.";
            
            foreach ($admins as $admin) {
                Notification::create([
                    'id_user' => $admin->id_user,
                    'type' => 'storage_warning',
                    'priority' => 'high',
                    'title' => 'Peringatan Storage',
                    'message' => $message,
                    'related_id' => null,
                    'created_at' => now(),
                ]);
            }
        }
    }

    /**
     * Get notification stats for admin dashboard
     */
    public static function getNotificationStats($adminId)
    {
        $unreadCount = Notification::where('id_user', $adminId)
            ->where('is_read', false)
            ->count();
        
        $highPriorityCount = Notification::where('id_user', $adminId)
            ->where('is_read', false)
            ->where('priority', 'high')
            ->count();
        
        $todayCount = Notification::where('id_user', $adminId)
            ->where('created_at', '>=', now()->startOfDay())
            ->count();
        
        return [
            'unread' => $unreadCount,
            'high_priority' => $highPriorityCount,
            'today' => $todayCount,
        ];
    }
}
