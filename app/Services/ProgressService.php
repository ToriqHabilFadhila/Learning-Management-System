<?php

namespace App\Services;

use App\Models\Progress;
use App\Models\Submission;
use App\Models\Assignment;
use App\Models\ClassEnrollment;

class ProgressService
{
    /**
     * Update progress siswa di kelas tertentu
     */
    public static function updateProgress($userId, $classId)
    {
        $progress = Progress::firstOrCreate(
            ['id_user' => $userId, 'id_class' => $classId],
            ['persentase' => 0, 'status' => 'in_progress']
        );

        // Hitung persentase berdasarkan submission
        $persentase = self::calculatePercentage($userId, $classId);
        
        // Update progress
        $progress->update([
            'persentase' => $persentase,
            'status' => $persentase >= 100 ? 'completed' : 'in_progress',
            'last_activity' => now(),
        ]);

        return $progress;
    }

    /**
     * Hitung persentase progress
     */
    private static function calculatePercentage($userId, $classId)
    {
        // Ambil semua assignment di kelas
        $totalAssignments = Assignment::where('id_class', $classId)
            ->where('is_published', true)
            ->count();

        if ($totalAssignments === 0) {
            return 0;
        }

        // Ambil submission siswa yang sudah graded
        $completedAssignments = Submission::where('id_user', $userId)
            ->whereHas('assignment', function ($q) use ($classId) {
                $q->where('id_class', $classId)
                  ->where('is_published', true);
            })
            ->where('status', 'graded')
            ->distinct('id_assignment')
            ->count();

        // Hitung persentase
        $persentase = ($completedAssignments / $totalAssignments) * 100;

        return min((int) round($persentase), 100);
    }

    /**
     * Update progress untuk semua siswa di kelas
     */
    public static function updateClassProgress($classId)
    {
        $enrollments = ClassEnrollment::where('id_class', $classId)
            ->where('status', 'active')
            ->get();

        foreach ($enrollments as $enrollment) {
            self::updateProgress($enrollment->id_user, $classId);
        }
    }

    /**
     * Get progress summary untuk dashboard
     */
    public static function getProgressSummary($userId)
    {
        $progresses = Progress::where('id_user', $userId)
            ->with('class')
            ->orderBy('persentase', 'desc')
            ->get();

        $summary = [
            'total_classes' => $progresses->count(),
            'completed_classes' => $progresses->where('status', 'completed')->count(),
            'in_progress_classes' => $progresses->where('status', 'in_progress')->count(),
            'avg_progress' => (int) round($progresses->avg('persentase')),
            'classes' => $progresses->map(function ($p) {
                return [
                    'class_name' => $p->class->nama_kelas,
                    'persentase' => $p->persentase,
                    'status' => $p->status,
                    'progress_status' => $p->progress_status,
                    'last_activity' => $p->last_activity?->format('d M Y H:i'),
                ];
            })->toArray(),
        ];

        return $summary;
    }

    /**
     * Get detailed progress untuk kelas tertentu
     */
    public static function getClassProgress($classId)
    {
        $progresses = Progress::where('id_class', $classId)
            ->with('user')
            ->orderBy('persentase', 'desc')
            ->get();

        $summary = [
            'total_students' => $progresses->count(),
            'completed' => $progresses->where('status', 'completed')->count(),
            'in_progress' => $progresses->where('status', 'in_progress')->count(),
            'avg_progress' => (int) round($progresses->avg('persentase')),
            'students' => $progresses->map(function ($p) {
                return [
                    'student_name' => $p->user->nama,
                    'student_id' => $p->user->id_user,
                    'persentase' => $p->persentase,
                    'status' => $p->status,
                    'progress_status' => $p->progress_status,
                    'last_activity' => $p->last_activity?->format('d M Y H:i'),
                ];
            })->toArray(),
        ];

        return $summary;
    }

    /**
     * Reset progress (untuk testing atau reset kelas)
     */
    public static function resetProgress($userId, $classId)
    {
        Progress::where('id_user', $userId)
            ->where('id_class', $classId)
            ->delete();
    }
}
