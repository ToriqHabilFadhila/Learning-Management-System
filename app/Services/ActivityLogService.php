<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    public static function log($actionType, $targetType = null, $targetId = null, $description = null)
    {
        if (!Auth::check()) {
            return;
        }

        ActivityLog::create([
            'id_user' => Auth::id(),
            'action_type' => $actionType,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'description' => $description,
            'ip_address' => Request::ip(),
            'timestamp' => now(),
        ]);
    }

    public static function logLogin()
    {
        self::log('login', null, null, 'User login ke sistem');
    }

    public static function logLogout()
    {
        self::log('logout', null, null, 'User logout dari sistem');
    }

    public static function logJoinClass($classId, $className, $tokenUsed)
    {
        self::log('join_class', 'class', $classId, "Bergabung dengan kelas '{$className}' menggunakan token {$tokenUsed}");
    }

    public static function logSubmitAssignment($assignmentId, $assignmentTitle, $type)
    {
        self::log('submit_assignment', 'assignment', $assignmentId, "Mengumpulkan tugas '{$assignmentTitle}' (tipe: {$type})");
    }

    public static function logCreateClass($classId, $className)
    {
        self::log('create_class', 'class', $classId, "Membuat kelas baru '{$className}'");
    }

    public static function logCreateAssignment($assignmentId, $assignmentTitle, $type)
    {
        self::log('create_assignment', 'assignment', $assignmentId, "Membuat tugas '{$assignmentTitle}' (tipe: {$type})");
    }

    public static function logPublishAssignment($assignmentId, $assignmentTitle)
    {
        self::log('publish_assignment', 'assignment', $assignmentId, "Mempublikasi tugas '{$assignmentTitle}'");
    }

    public static function logGradeSubmission($submissionId, $assignmentTitle, $score)
    {
        self::log('grade_submission', 'submission', $submissionId, "Menilai submission untuk tugas '{$assignmentTitle}' dengan nilai {$score}");
    }

    public static function logUploadMaterial($materialId, $materialTitle)
    {
        self::log('upload_material', 'material', $materialId, "Upload materi '{$materialTitle}'");
    }

    public static function logGenerateQuestions($assignmentId, $count)
    {
        self::log('generate_questions', 'assignment', $assignmentId, "Generate {$count} soal menggunakan AI");
    }

    public static function logRegenerateToken($classId, $className)
    {
        self::log('regenerate_token', 'class', $classId, "Regenerate token untuk kelas '{$className}'");
    }

    public static function logDeleteAssignment($assignmentId, $assignmentTitle)
    {
        self::log('delete_assignment', 'assignment', $assignmentId, "Menghapus tugas '{$assignmentTitle}'");
    }

    public static function logDeleteQuestion($questionId, $assignmentId)
    {
        self::log('delete_question', 'question', $questionId, "Menghapus soal dari assignment {$assignmentId}");
    }

    public static function logUpdateProfile()
    {
        self::log('update_profile', null, null, 'Update profil pengguna');
    }

    public static function logChangePassword()
    {
        self::log('change_password', null, null, 'Mengubah password');
    }
}
