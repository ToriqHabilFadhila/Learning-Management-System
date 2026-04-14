<?php

namespace App\Services;

use App\Models\Submission;
use App\Models\Assignment;
use App\Models\User;
use App\Models\Classes;
use Carbon\Carbon;

class AIAnalysisService
{
    public function analyzeStudentPerformance($userId, $classId)
    {
        $user = User::findOrFail($userId);
        $class = Classes::findOrFail($classId);

        $submissions = Submission::where('id_user', $userId)
            ->whereHas('assignment', function ($q) use ($classId) {
                $q->where('id_class', $classId);
            })
            ->with('assignment')
            ->get();

        $metrics = $this->calculateMetrics($submissions, $user, $class);
        $analysis = $this->generateAnalysis($metrics);
        $recommendations = $this->generateRecommendations($metrics);

        return [
            'student_name' => $user->nama,
            'class_name' => $class->nama_kelas,
            'metrics' => $metrics,
            'analysis' => $analysis,
            'recommendations' => $recommendations,
            'generated_at' => now()->format('d M Y H:i'),
        ];
    }

    private function calculateMetrics($submissions, $user, $class)
    {
        $total = $submissions->count();
        $completed = $submissions->where('status', 'graded')->count();
        $pending = $submissions->where('status', 'submitted')->count();
        $avgScore = $submissions->where('status', 'graded')->avg('score') ?? 0;
        $maxScore = $submissions->where('status', 'graded')->max('score') ?? 0;
        $minScore = $submissions->where('status', 'graded')->min('score') ?? 0;

        $late = $submissions->filter(function ($sub) {
            return $sub->submitted_at && $sub->assignment->deadline &&
                $sub->submitted_at->greaterThan($sub->assignment->deadline);
        })->count();

        $onTime = $completed - $late;
        $completionRate = $total > 0 ? round(($completed / $total) * 100, 1) : 0;
        $onTimeRate = $completed > 0 ? round(($onTime / $completed) * 100, 1) : 0;

        // Analisis per tipe tugas
        $byType = [];
        foreach (['pilihan_ganda', 'essay', 'praktik'] as $type) {
            $typeSubmissions = $submissions->filter(function ($sub) use ($type) {
                return $sub->assignment->tipe === $type;
            });
            $typeCompleted = $typeSubmissions->where('status', 'graded')->count();
            $typeAvg = $typeSubmissions->where('status', 'graded')->avg('score') ?? 0;
            $byType[$type] = [
                'total' => $typeSubmissions->count(),
                'completed' => $typeCompleted,
                'avg_score' => round($typeAvg, 1),
            ];
        }

        // Tren performa (ascending/descending)
        $recentScores = $submissions->where('status', 'graded')
            ->sortBy('submitted_at')
            ->pluck('score')
            ->values()
            ->toArray();
        $trend = $this->calculateTrend($recentScores);

        // Konsistensi
        $variance = $this->calculateVariance($recentScores);
        $consistency = $variance < 10 ? 'Sangat Konsisten' : ($variance < 20 ? 'Konsisten' : 'Fluktuatif');

        return [
            'total_assignments' => $total,
            'completed' => $completed,
            'pending' => $pending,
            'avg_score' => round($avgScore, 1),
            'max_score' => $maxScore,
            'min_score' => $minScore,
            'completion_rate' => $completionRate,
            'on_time' => $onTime,
            'late' => $late,
            'on_time_rate' => $onTimeRate,
            'by_type' => $byType,
            'trend' => $trend,
            'consistency' => $consistency,
            'variance' => round($variance, 1),
            'recent_scores' => $recentScores,
        ];
    }

    private function generateAnalysis($metrics)
    {
        $analysis = [];

        // Analisis Performa Umum
        $avgScore = $metrics['avg_score'];
        if ($avgScore >= 85) {
            $analysis[] = "Siswa menunjukkan performa yang sangat baik dengan rata-rata nilai {$metrics['avg_score']}. Kemampuan akademik siswa sudah mencapai level yang excellent.";
        } elseif ($avgScore >= 75) {
            $analysis[] = "Siswa menunjukkan performa yang baik dengan rata-rata nilai {$metrics['avg_score']}. Siswa sudah menguasai materi dengan cukup baik.";
        } elseif ($avgScore >= 65) {
            $analysis[] = "Siswa menunjukkan performa yang cukup dengan rata-rata nilai {$metrics['avg_score']}. Masih ada ruang untuk peningkatan.";
        } else {
            $analysis[] = "Siswa menunjukkan performa yang perlu ditingkatkan dengan rata-rata nilai {$metrics['avg_score']}. Diperlukan intervensi khusus.";
        }

        // Analisis Ketepatan Waktu
        if ($metrics['on_time_rate'] >= 90) {
            $analysis[] = "Siswa sangat disiplin dalam mengumpulkan tugas tepat waktu ({$metrics['on_time_rate']}%). Manajemen waktu siswa sangat baik.";
        } elseif ($metrics['on_time_rate'] >= 70) {
            $analysis[] = "Siswa umumnya mengumpulkan tugas tepat waktu ({$metrics['on_time_rate']}%), meskipun ada beberapa keterlambatan.";
        } else {
            $analysis[] = "Siswa sering mengumpulkan tugas terlambat ({$metrics['late']} dari {$metrics['completed']} tugas). Perlu perbaikan manajemen waktu.";
        }

        // Analisis Konsistensi
        $analysis[] = "Performa siswa tergolong {$metrics['consistency']} dengan variance {$metrics['variance']}. " .
            ($metrics['consistency'] === 'Sangat Konsisten' ?
                "Siswa menunjukkan stabilitas yang baik dalam belajar." :
                "Performa siswa cukup berfluktuasi, perlu lebih fokus dan konsisten.");

        // Analisis Tren
        if ($metrics['trend'] === 'meningkat') {
            $analysis[] = "Tren performa siswa menunjukkan peningkatan positif. Siswa semakin memahami materi seiring waktu.";
        } elseif ($metrics['trend'] === 'menurun') {
            $analysis[] = "Tren performa siswa menunjukkan penurunan. Perlu identifikasi masalah dan intervensi segera.";
        } else {
            $analysis[] = "Tren performa siswa relatif stabil. Siswa mempertahankan level performa yang konsisten.";
        }

        // Analisis per Tipe Tugas
        $byType = $metrics['by_type'];
        $bestType = array_key_first(array_filter($byType, function ($t) {
            return $t['completed'] > 0;
        }, ARRAY_FILTER_USE_BOTH));
        if ($bestType) {
            $bestScore = $byType[$bestType]['avg_score'];
            $analysis[] = "Siswa menunjukkan kekuatan khusus pada tugas tipe " . ucfirst(str_replace('_', ' ', $bestType)) .
                " dengan rata-rata nilai {$bestScore}.";
        }

        return $analysis;
    }

    private function generateRecommendations($metrics)
    {
        $recommendations = [];

        // Rekomendasi berdasarkan nilai
        if ($metrics['avg_score'] < 70) {
            $recommendations[] = [
                'priority' => 'high',
                'title' => 'Penguatan Materi Dasar',
                'description' => 'Siswa perlu mengikuti sesi penguatan materi dasar untuk meningkatkan pemahaman fundamental.',
                'action' => 'Guru dapat memberikan tugas tambahan atau sesi bimbingan khusus.'
            ];
        }

        // Rekomendasi berdasarkan ketepatan waktu
        if ($metrics['on_time_rate'] < 80) {
            $recommendations[] = [
                'priority' => 'high',
                'title' => 'Manajemen Waktu',
                'description' => 'Siswa perlu meningkatkan manajemen waktu dalam mengerjakan tugas.',
                'action' => 'Buat jadwal belajar yang terstruktur dan ingatkan deadline sebelumnya.'
            ];
        }

        // Rekomendasi berdasarkan konsistensi
        if ($metrics['variance'] > 20) {
            $recommendations[] = [
                'priority' => 'medium',
                'title' => 'Konsistensi Belajar',
                'description' => 'Performa siswa cukup fluktuatif. Perlu lebih konsisten dalam belajar.',
                'action' => 'Buat rutinitas belajar harian dan hindari belajar dadakan.'
            ];
        }

        // Rekomendasi untuk siswa berprestasi
        if ($metrics['avg_score'] >= 85) {
            $recommendations[] = [
                'priority' => 'low',
                'title' => 'Pengayaan Materi',
                'description' => 'Siswa sudah menguasai materi dengan baik. Berikan materi pengayaan untuk tantangan lebih.',
                'action' => 'Tawarkan proyek khusus atau materi lanjutan yang lebih menantang.'
            ];
        }

        // Rekomendasi berdasarkan tren
        if ($metrics['trend'] === 'menurun') {
            $recommendations[] = [
                'priority' => 'high',
                'title' => 'Identifikasi Masalah',
                'description' => 'Performa siswa menunjukkan tren menurun. Perlu identifikasi penyebab masalah.',
                'action' => 'Lakukan konsultasi dengan siswa untuk mengetahui hambatan yang dihadapi.'
            ];
        }

        // Rekomendasi untuk tugas pending
        if ($metrics['pending'] > 0) {
            $recommendations[] = [
                'priority' => 'high',
                'title' => 'Selesaikan Tugas Pending',
                'description' => "Siswa masih memiliki {$metrics['pending']} tugas yang belum diselesaikan.",
                'action' => 'Ingatkan siswa untuk segera menyelesaikan tugas yang tertunda.'
            ];
        }

        return $recommendations;
    }

    private function calculateTrend($scores)
    {
        if (count($scores) < 2) {
            return 'stabil';
        }

        $firstHalf = array_slice($scores, 0, (int) (count($scores) / 2));
        $secondHalf = array_slice($scores, (int) (count($scores) / 2));

        $avgFirst = array_sum($firstHalf) / count($firstHalf);
        $avgSecond = array_sum($secondHalf) / count($secondHalf);

        $diff = $avgSecond - $avgFirst;

        if ($diff > 5) {
            return 'meningkat';
        } elseif ($diff < -5) {
            return 'menurun';
        } else {
            return 'stabil';
        }
    }

    private function calculateVariance($scores)
    {
        if (count($scores) < 2) {
            return 0;
        }

        $mean = array_sum($scores) / count($scores);
        $squaredDiffs = array_map(function ($score) use ($mean) {
            return pow($score - $mean, 2);
        }, $scores);

        return array_sum($squaredDiffs) / count($squaredDiffs);
    }
}
