<?php

namespace App\Services;

use App\Models\FeedbackAI;
use App\Models\Submission;
use App\Services\CacheService;
use App\Services\HuggingFaceService;
use App\Services\AIAnalysisService;
use Illuminate\Support\Facades\Log;

class FeedbackAIService
{
    protected $analysisService;
    protected $materialRecommendationService;
    protected $huggingFaceService;

    public function __construct(MaterialRecommendationService $materialRecommendationService,?HuggingFaceService $huggingFaceService = null) {
        $this->materialRecommendationService = $materialRecommendationService;
        $this->huggingFaceService = $huggingFaceService ?? new HuggingFaceService();
        $this->analysisService = new AIAnalysisService();
    }

    public function generateAndSaveFeedback(int $userId, int $classId): array
    {
        try {
            if ($this->hasExistingFeedback($userId, $classId)) {
                return $this->responseExists();
            }

            $analysis = $this->analyze($userId, $classId);
            $submissions = $this->getSubmissions($userId, $classId);

            $savedCount = $this->processSubmissions($submissions, $analysis, $userId);

            return $this->successResponse($savedCount, $submissions->count());

        } catch (\Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    private function analyze(int $userId, int $classId): array
    {
        return $this->analysisService->analyzeStudentPerformance($userId, $classId);
    }

    private function hasExistingFeedback(int $userId, int $classId): bool
    {
        return FeedbackAI::whereHas('submission', function ($q) use ($userId, $classId) {
            $q->where('id_user', $userId)
            ->whereHas('assignment', fn($subQ) => $subQ->where('id_class', $classId));
        })->exists();
    }

    private function getSubmissions(int $userId, int $classId)
    {
        return Submission::where('id_user', $userId)
            ->whereHas('assignment', fn($q) => $q->where('id_class', $classId))
            ->where(function ($query) {
                $query->where('status', 'graded')
                    ->orWhere(fn($q) =>
                        $q->where('status', 'submitted')
                        ->whereHas('assignment', fn($subQ) => $subQ->where('tipe', 'pilihan_ganda'))
                    );
            })
            ->with('assignment.class')
            ->latest('submitted_at')
            ->limit(5)
            ->get();
    }

    private function processSubmissions($submissions, array $analysis, int $userId): int
    {
        $existingIds = FeedbackAI::whereIn('id_submission', $submissions->pluck('id_submission'))
            ->pluck('id_submission')
            ->toArray();

        $profile = $this->buildStudentProfile($submissions, null, $analysis);
        $aiRaw = $this->huggingFaceService->recommendMaterials($profile);
        $recommendations = $this->parseAIRecommendations($aiRaw);

        $savedCount = 0;

        foreach ($submissions as $submission) {
            if (in_array($submission->id_submission, $existingIds)) {
                continue;
            }

            $this->saveFeedback($submission, $analysis, $recommendations);
            $savedCount++;
        }

        CacheService::invalidateUserRecommendations($userId);

        return $savedCount;
    }

    private function saveFeedback($submission, array $analysis, array $recommendations): void
    {
        $metrics = $analysis['metrics'];
        
        // Format analysis dengan paragraf yang rapi
        $analysisText = implode("\n\n", $analysis['analysis']);
        
        FeedbackAI::create([
            'id_submission' => $submission->id_submission,
            'feedback_text' => $this->formatStudentProfile($analysis),
            'saran' => $analysisText,
            'rekomendasi_materi' => $this->formatRecommendations($recommendations),
            'created_at' => now(),
        ]);
    }

    private function successResponse(int $saved, int $total): array
    {
        return [
            'success' => true,
            'message' => "Feedback AI berhasil dibuat untuk {$saved} submission",
            'total_submissions' => $total,
            'saved_count' => $saved,
        ];
    }

    private function responseExists(): array
    {
        return [
            'success' => true,
            'message' => 'Feedback sudah ada',
            'total_submissions' => 0,
            'saved_count' => 0,
        ];
    }

    private function errorResponse(\Throwable $e): array
    {
        Log::error($e->getMessage(), ['trace' => $e->getTraceAsString()]);

        return [
            'success' => false,
            'message' => 'Error generating feedback',
        ];
    }

    private function buildStudentProfile($submissions, $classId, $analysis)
    {
        $avgScore = $submissions->avg('score') ?? 0;
        $totalSubmissions = $submissions->count();
        $completedCount = $submissions->where('status', 'graded')->count();
        $className = $analysis['class_name'] ?? 'Umum';

        return [
            'subject' => $className,
            'last_scores' => $submissions->pluck('score')->filter()->implode(', ') ?: 'Belum ada',
            'avg_score' => round($avgScore, 1),
            'progress' => "{$completedCount} dari {$totalSubmissions} tugas",
            'performance_status' => $this->getPerformanceStatus($avgScore),
            'weak_topics' => $submissions->filter(fn($sub) => $sub->score < 70)->pluck('assignment.judul')->take(3)->implode(', ') ?: 'Tidak ada',
            'learning_style' => $avgScore >= 80 ? 'Visual & Praktik' : 'Perlu Penguatan Dasar',
            'available_materials' => $this->getAvailableMaterials($classId)
        ];
    }

    private function getPerformanceStatus($avgScore)
    {
        if ($avgScore < 60)
            return 'Perlu Perhatian Khusus';
        if ($avgScore >= 80)
            return 'Baik';
        return 'Cukup';
    }

    private function getAvailableMaterials($classId)
    {
        $materials = \App\Models\Material::where('id_class', $classId)
            ->get()
            ->pluck('judul')
            ->implode(', ');

        return $materials ?: 'Belum ada materi';
    }

    private function parseAIRecommendations($aiText)
    {
        $recommendations = [];

        // Parse numbered recommendations from AI text
        if (preg_match_all('/\d+\.\s*\*\*(.+?)\*\*\s*-\s*(.+?)(?=\n\d+\.|$)/s', $aiText, $matches)) {
            foreach ($matches[1] as $index => $title) {
                $recommendations[] = [
                    'title' => trim($title),
                    'description' => trim($matches[2][$index]),
                    'resources' => 'Tutorial | Video | Dokumentasi'
                ];
            }
        }

        // If no structured recommendations found, create a generic one
        if (empty($recommendations)) {
            $recommendations[] = [
                'title' => 'Materi Pembelajaran Umum',
                'description' => $aiText,
                'resources' => 'Tutorial | Video | Dokumentasi'
            ];
        }

        return $recommendations;
    }

    private function formatStudentProfile($analysis)
    {
        $metrics = $analysis['metrics'];

        $text = "Profil & Progress Belajar\n";
        $text .= "========================\n\n";
        $text .= "Mata Pelajaran: {$analysis['class_name']}\n";
        $text .= "Nilai Terakhir: {$metrics['max_score']}\n";
        $text .= "Rata-rata Nilai: {$metrics['avg_score']}\n";
        $text .= "Progress Belajar: {$metrics['completed']} dari {$metrics['total_assignments']} tugas\n";
        $text .= "Completion Rate: {$metrics['completion_rate']}%\n";
        $text .= "On-Time Rate: {$metrics['on_time_rate']}%\n";
        $text .= "Trend: {$metrics['trend']}\n";
        $text .= "Consistency: {$metrics['consistency']}\n";

        return $text;
    }

    private function formatRecommendations($recommendations)
    {
        // Simpan dalam format JSON untuk parsing yang lebih baik
        return json_encode($recommendations, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    private function generateSuggestions($submission, $metrics)
    {
        $suggestions = [];
        $score = $submission->score ?? 0;

        if ($score < 70) {
            $suggestions[] = "Baca kembali materi pembelajaran dan coba kerjakan soal latihan tambahan untuk memperkuat pemahaman.";
        }

        if ($metrics['on_time_rate'] < 80) {
            $suggestions[] = "Coba buat jadwal belajar yang lebih terstruktur agar dapat mengumpulkan tugas tepat waktu.";
        }

        if ($metrics['variance'] > 20) {
            $suggestions[] = "Tingkatkan konsistensi belajar Anda dengan membuat rutinitas harian yang teratur.";
        }

        if ($metrics['trend'] === 'menurun') {
            $suggestions[] = "Performa Anda menunjukkan tren menurun. Identifikasi hambatan yang Anda hadapi dan minta bantuan guru jika diperlukan.";
        }

        if (empty($suggestions)) {
            $suggestions[] = "Pertahankan performa Anda yang sudah baik dengan terus belajar dan berlatih secara konsisten.";
        }

        return implode(' ', $suggestions);
    }
}
