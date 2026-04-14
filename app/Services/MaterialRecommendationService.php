<?php

namespace App\Services;

class MaterialRecommendationService
{
    public function generateMaterialRecommendations($metrics)
    {
        $recommendations = [];

        // Rekomendasi berdasarkan nilai rata-rata
        if ($metrics['avg_score'] < 60) {
            $recommendations[] = $this->createRecommendation(
                'Fundamentals & Basics',
                'Pelajari kembali konsep dasar dan definisi-definisi kunci dalam topik ini. Fokus pada pemahaman fundamental sebelum melanjutkan ke materi yang lebih kompleks.',
                'Buku: Chapter 1-3 | Video: Introduction Series | Quiz: Basic Concepts'
            );
        } elseif ($metrics['avg_score'] < 75) {
            $recommendations[] = $this->createRecommendation(
                'Core Concepts & Applications',
                'Perkuat pemahaman tentang konsep inti dan aplikasinya dalam konteks nyata. Lakukan latihan soal tambahan untuk meningkatkan penguasaan.',
                'Buku: Chapter 4-6 | Video: Concept Deep Dive | Exercises: Intermediate Level'
            );
        }

        // Rekomendasi berdasarkan ketepatan waktu
        if ($metrics['on_time_rate'] < 70) {
            $recommendations[] = $this->createRecommendation(
                'Time Management & Study Planning',
                'Pelajari teknik manajemen waktu dan buat rencana belajar yang terstruktur. Identifikasi hambatan yang menyebabkan keterlambatan.',
                'Resource: Time Management Guide | Tool: Study Planner | Workshop: Productivity Tips'
            );
        }

        // Rekomendasi berdasarkan konsistensi
        if ($metrics['variance'] > 20) {
            $recommendations[] = $this->createRecommendation(
                'Consistent Learning Habits',
                'Bangun kebiasaan belajar yang konsisten dan teratur. Hindari belajar dadakan dan fokus pada pembelajaran jangka panjang.',
                'Guide: Building Study Habits | Checklist: Daily Learning Routine | Tracker: Progress Monitor'
            );
        }

        // Rekomendasi berdasarkan tren
        if ($metrics['trend'] === 'menurun') {
            $recommendations[] = $this->createRecommendation(
                'Review & Reinforcement',
                'Lakukan review menyeluruh terhadap materi yang telah dipelajari. Identifikasi topik-topik yang menjadi sumber kesulitan.',
                'Resource: Comprehensive Review | Video: Topic Recap | Practice: Challenging Problems'
            );
        } elseif ($metrics['trend'] === 'meningkat') {
            $recommendations[] = $this->createRecommendation(
                'Advanced Topics & Extensions',
                'Lanjutkan dengan materi tingkat lanjut dan topik-topik pengembangan. Anda sudah siap untuk tantangan yang lebih kompleks.',
                'Buku: Advanced Chapters | Video: Expert Level | Project: Real-world Application'
            );
        }

        // Rekomendasi berdasarkan performa per tipe tugas
        $weakType = $this->findWeakestAssignmentType($metrics['by_type']);
        if ($weakType) {
            $recommendations[] = $this->createTypeSpecificRecommendation($weakType, $metrics['by_type'][$weakType]);
        }

        // Rekomendasi untuk siswa berprestasi
        if ($metrics['avg_score'] >= 85 && $metrics['completion_rate'] >= 90) {
            $recommendations[] = $this->createRecommendation(
                'Enrichment & Specialization',
                'Anda telah menguasai materi dengan sangat baik. Jelajahi topik-topik khusus dan spesialisasi yang menarik minat Anda.',
                'Resource: Specialized Topics | Seminar: Expert Talks | Project: Independent Research'
            );
        }

        // Rekomendasi untuk tugas pending
        if ($metrics['pending'] > 0) {
            $recommendations[] = $this->createRecommendation(
                'Complete Pending Tasks',
                "Prioritaskan penyelesaian {$metrics['pending']} tugas yang masih pending. Jangan biarkan tugas menumpuk.",
                'Action: Review Pending List | Deadline: Check Due Dates | Support: Ask for Help if Needed'
            );
        }

        return $recommendations;
    }

    private function createRecommendation($title, $description, $resources)
    {
        return [
            'title' => $title,
            'description' => $description,
            'resources' => $resources,
        ];
    }

    private function findWeakestAssignmentType($byType)
    {
        $weakest = null;
        $lowestScore = 100;

        foreach ($byType as $type => $data) {
            if ($data['completed'] > 0 && $data['avg_score'] < $lowestScore) {
                $lowestScore = $data['avg_score'];
                $weakest = $type;
            }
        }

        return $weakest;
    }

    private function createTypeSpecificRecommendation($type, $typeData)
    {
        $typeLabel = ucfirst(str_replace('_', ' ', $type));
        $avgScore = $typeData['avg_score'];

        $descriptions = [
            'pilihan_ganda' => 'Tugas pilihan ganda memerlukan pemahaman yang cepat dan akurat. Latih kemampuan analisis dan eliminasi opsi yang salah.',
            'essay' => 'Tugas essay memerlukan kemampuan menulis dan argumentasi yang kuat. Fokus pada struktur, logika, dan penggunaan bahasa yang tepat.',
            'praktik' => 'Tugas praktik memerlukan aplikasi langsung dari teori. Lakukan eksperimen berulang dan dokumentasikan proses pembelajaran Anda.',
        ];

        $resources = [
            'pilihan_ganda' => 'Video: Multiple Choice Strategy | Practice: Timed Quizzes | Guide: Test Taking Tips',
            'essay' => 'Guide: Essay Writing Structure | Video: Argumentation Techniques | Practice: Essay Samples',
            'praktik' => 'Tutorial: Step-by-Step Guide | Video: Practical Demonstrations | Resource: Tools & Equipment Guide',
        ];

        return [
            'title' => "Improve {$typeLabel} Skills",
            'description' => $descriptions[$type] ?? 'Tingkatkan kemampuan Anda dalam tipe tugas ini dengan latihan dan review yang lebih intensif.',
            'resources' => $resources[$type] ?? 'Practice: Additional Exercises | Video: Technique Review | Consultation: One-on-One Session',
        ];
    }
}
