<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HuggingFaceService
{
    private string $apiKey;
    private string $apiUrl;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.huggingface.api_key');
        if (!$this->apiKey) {
            throw new \Exception('HUGGINGFACE_API_KEY tidak ditemukan');
        }
        $this->apiUrl = 'https://router.huggingface.co/v1/chat/completions';
        $this->model  = 'meta-llama/Meta-Llama-3-8B-Instruct';
    }

    private function callAPI(string $system, string $user, int $maxTokens = 256, float $temperature = 0.3)
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(90)
                ->post($this->apiUrl, [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'system', 'content' => $system],
                        ['role' => 'user', 'content' => $user],
                    ],
                    'max_tokens' => $maxTokens,
                    'temperature' => $temperature,
                    'top_p' => 0.9,
                    'seed' => 42,
                ]);
            if ($response->failed()) {
                Log::error('HF Router Error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return null;
            }
            return $response->json()['choices'][0]['message']['content'] ?? null;
        } catch (\Throwable $e) {
            Log::error('HF Exception: '.$e->getMessage());
            return null;
        }
    }

    /* =========================
        USE CASES
    ========================= */

    public function analyzeStudentProgress(array $data)
    {
        $system = "Anda adalah guru berpengalaman yang memberikan analisis progres siswa yang konstruktif dan mendorong. \n" .
            "Instruksi:\n" .
            "1. Analisis data dengan objektif dan profesional\n" .
            "2. Identifikasi kekuatan dan area yang perlu ditingkatkan\n" .
            "3. Berikan rekomendasi aksi konkret dan terukur\n" .
            "4. Gunakan bahasa yang positif dan memotivasi\n" .
            "5. Hindari markdown, simbol **, atau format khusus\n" .
            "6. Jawab dalam 3-4 paragraf singkat";
        
        $performanceLevel = $data['avg_score'] >= 80 ? 'Sangat Baik' : ($data['avg_score'] >= 70 ? 'Baik' : ($data['avg_score'] >= 60 ? 'Cukup' : 'Perlu Perhatian'));
        $completionRate = $data['total'] > 0 ? round(($data['completed'] / $data['total']) * 100) : 0;
        
        $user =
            "DATA SISWA:\n" .
            "Nama: {$data['nama']}\n" .
            "Kelas: {$data['kelas']}\n" .
            "Tingkat Performa: {$performanceLevel}\n" .
            "Tugas Selesai: {$data['completed']}/{$data['total']} ({$completionRate}%)\n" .
            "Rata-rata Nilai: {$data['avg_score']}/100\n" .
            "Tugas Terlambat: {$data['late']}\n\n" .
            "DIMINTA:\n" .
            "1. Analisis performa siswa (1-2 kalimat)\n" .
            "2. Identifikasi area kekuatan dan kelemahan (1-2 kalimat)\n" .
            "3. Rekomendasi tindakan spesifik (2-3 poin konkret)";
        
        $response = $this->callAPI($system, $user, 250, 0.4);
        return $response ? str_replace(['**', '*', '##', '#'], '', $response) : $response;
    }

    public function provideFeedback(string $question, string $answer)
    {
        $system = "Anda adalah guru berpengalaman yang memberikan feedback konstruktif dan mendorong untuk jawaban siswa.\n" .
            "Instruksi:\n" .
            "1. Mulai dengan mengakui hal positif dari jawaban\n" .
            "2. Identifikasi area yang dapat ditingkatkan dengan spesifik\n" .
            "3. Berikan saran konkret untuk perbaikan\n" .
            "4. Akhiri dengan motivasi positif\n" .
            "5. Gunakan bahasa yang ramah dan mendukung\n" .
            "6. Jawab dalam 3-4 kalimat singkat";
        
        $user = "PERTANYAAN:\n$question\n\n" .
            "JAWABAN SISWA:\n$answer\n\n" .
            "Berikan feedback yang konstruktif, spesifik, dan memotivasi.";
        
        return $this->callAPI($system, $user, 180, 0.5);
    }

    public function gradeAnswer(string $question, string $key, string $answer, int $maxScore)
    {
        $system = "Anda adalah sistem penilaian otomatis yang adil dan objektif.\n" .
            "INSTRUKSI PENTING:\n" .
            "1. Bandingkan jawaban siswa dengan kunci jawaban\n" .
            "2. Berikan skor berdasarkan akurasi, kelengkapan, dan kedalaman\n" .
            "3. Skor harus antara 0 dan nilai maksimal yang diberikan\n" .
            "4. Feedback harus spesifik dan membantu siswa memahami kesalahan\n" .
            "5. WAJIB BALAS DALAM FORMAT JSON SAJA: {\"score\":angka,\"feedback\":\"teks\"}\n" .
            "6. Jangan tambahkan teks lain di luar JSON";
        
        $user = "SOAL:\n$question\n\n" .
            "KUNCI JAWABAN:\n$key\n\n" .
            "JAWABAN SISWA:\n$answer\n\n" .
            "NILAI MAKSIMAL: $maxScore\n\n" .
            "Berikan penilaian dalam format JSON dengan score dan feedback yang jelas.";

        $raw = $this->callAPI($system, $user, 150, 0.2);

        if (!$raw) {
            return ['score' => 0, 'feedback' => 'AI tidak merespons'];
        }

        // Extract JSON dari response
        if (preg_match('/{[^}]*"score"[^}]*"feedback"[^}]*}/', $raw, $match)) {
            $json = json_decode($match[0], true);
            if (is_array($json) && isset($json['score'])) {
                $feedback = trim($json['feedback'] ?? '');
                if (empty($feedback)) {
                    $feedback = 'Jawaban sudah dinilai dengan skor ' . $json['score'];
                }
                return [
                    'score' => min(max((int)$json['score'], 0), $maxScore),
                    'feedback' => $feedback
                ];
            }
        }

        return ['score' => 0, 'feedback' => 'Format tidak valid'];
    }

    public function recommendMaterials(array $studentProfile)
    {
        $system = "Anda adalah asisten pembelajaran yang memberikan rekomendasi materi personal berdasarkan profil siswa.\n" .
            "INSTRUKSI:\n" .
            "1. Analisis profil siswa secara menyeluruh\n" .
            "2. Prioritaskan materi yang sesuai dengan kebutuhan dan gaya belajar\n" .
            "3. Untuk siswa dengan nilai rendah, fokus pada penguatan dasar\n" .
            "4. Untuk siswa berkinerja tinggi, tawarkan materi pengayaan\n" .
            "5. Berikan 3-4 rekomendasi dengan alasan singkat\n" .
            "6. Format: Nomor. Judul Materi - Alasan (1 kalimat)\n" .
            "7. Jawab SINGKAT dan PADAT tanpa penjelasan panjang";

        $performanceNote = '';
        if (isset($studentProfile['avg_score'])) {
            if ($studentProfile['avg_score'] < 60) {
                $performanceNote = "PRIORITAS: Nilai rata-rata rendah ({$studentProfile['avg_score']}). Fokus pada materi dasar dan penguatan fundamental.\n";
            } elseif ($studentProfile['avg_score'] >= 80) {
                $performanceNote = "PRIORITAS: Siswa berkinerja tinggi ({$studentProfile['avg_score']}). Tawarkan materi pengayaan dan tantangan.\n";
            }
        }

        $user =
            "PROFIL SISWA:\n" .
            $performanceNote .
            "Mata Pelajaran: {$studentProfile['subject']}\n" .
            "Nilai Terakhir: {$studentProfile['last_scores']}\n" .
            "Rata-rata Nilai: {$studentProfile['avg_score']}/100\n" .
            "Progress: {$studentProfile['progress']}\n" .
            "Status Performa: {$studentProfile['performance_status']}\n" .
            "Topik yang Sulit: {$studentProfile['weak_topics']}\n" .
            "Gaya Belajar: {$studentProfile['learning_style']}\n\n" .
            "MATERI TERSEDIA:\n" .
            "{$studentProfile['available_materials']}\n\n" .
            "Berikan 3-4 rekomendasi materi yang paling sesuai dengan profil siswa ini.";
        
        return $this->callAPI($system, $user, 350, 0.4);
    }
    
    public function chat(string $prompt)
    {
        $system = "Anda adalah asisten AI yang membantu guru dalam menganalisis performa siswa dan menjawab pertanyaan umum.\n" .
            "INSTRUKSI:\n" .
            "1. Jika pertanyaan terkait siswa/pendidikan, gunakan konteks yang diberikan\n" .
            "2. Jika pertanyaan di luar topik pendidikan, jawab dengan pengetahuan umum Anda\n" .
            "3. Berikan jawaban yang jelas, singkat, dan informatif\n" .
            "4. Gunakan bahasa yang profesional namun ramah\n" .
            "5. Hindari markdown atau format khusus\n" .
            "6. Jawab dalam 2-4 paragraf";
        
        $response = $this->callAPI($system, $prompt, 400, 0.6);
        return $response ? str_replace(['**', '*', '##', '#'], '', $response) : 'Maaf, saya tidak dapat menjawab pertanyaan tersebut saat ini.';
    }
}
