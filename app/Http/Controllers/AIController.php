<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HuggingFaceService;
use App\Services\AIAnalysisService;
use App\Services\ActivityLogService;
use App\Models\Submission;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\CacheService;

class AIController extends Controller
{
    protected $ai;
    public function __construct(HuggingFaceService $ai)
    {
        $this->ai = $ai;
    }

    public function analyzeProgress(Request $request, $userId, $classId)
    {
        try {
            $customQuestion = $request->input('question');
            
            Log::info('Analyze progress called', [
                'user_id' => $userId,
                'class_id' => $classId,
                'has_question' => !empty($customQuestion)
            ]);

            // If userId is 0, analyze entire class
            if ($userId == 0) {
                return $this->analyzeClassPerformance($classId, $customQuestion);
            }

            $existingFeedback = \App\Models\FeedbackAI::whereHas('submission', function ($q) use ($userId, $classId) {
                $q->where('id_user', $userId)
                    ->whereHas('assignment', function ($subQ) use ($classId) {
                        $subQ->where('id_class', $classId);
                    });
            })->first();

            if ($existingFeedback) {
                Log::info('Using existing feedback for guru analysis', [
                    'user_id' => $userId,
                    'class_id' => $classId
                ]);

                $analysis = $this->parseAnalysisFromFeedback($existingFeedback);
                
                if (!empty($customQuestion)) {
                    $contextualAnswer = $this->answerCustomQuestion($customQuestion, $analysis, $userId, $classId);
                    $analysis['custom_answer'] = $contextualAnswer;
                    
                    // Save question and answer to database
                    $this->saveQuestionToDatabase($userId, $classId, $customQuestion, $contextualAnswer['answer']);
                }

                return response()->json([
                    'success' => true,
                    'data' => $analysis,
                    'feedback_saved' => 0,
                    'from_cache' => true
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }

            $analysisService = new AIAnalysisService();
            $analysis = $analysisService->analyzeStudentPerformance($userId, $classId);

            $feedbackService = new \App\Services\FeedbackAIService(
                new \App\Services\MaterialRecommendationService(),
                new HuggingFaceService()
            );
            $feedbackResult = $feedbackService->generateAndSaveFeedback($userId, $classId);

            Log::info('Feedback AI generated', [
                'user_id' => $userId,
                'class_id' => $classId,
                'saved_count' => $feedbackResult['saved_count'] ?? 0
            ]);
            
            if (!empty($customQuestion)) {
                $contextualAnswer = $this->answerCustomQuestion($customQuestion, $analysis, $userId, $classId);
                $analysis['custom_answer'] = $contextualAnswer;
                
                // Save question and answer to database
                $this->saveQuestionToDatabase($userId, $classId, $customQuestion, $contextualAnswer['answer']);
            }

            ActivityLogService::log('analyze_student', 'user', $userId, "Menganalisis performa siswa di kelas {$classId}");

            return response()->json([
                'success' => true,
                'data' => $analysis,
                'feedback_saved' => $feedbackResult['saved_count'] ?? 0,
                'from_cache' => false
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            Log::error('AI Analysis Error: ' . $e->getMessage());
            Log::error('Stack: ' . $e->getTraceAsString());
            Log::error('Request params', [
                'user_id' => $userId,
                'class_id' => $classId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menganalisis performa siswa: ' . $e->getMessage()
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
    
    private function analyzeClassPerformance($classId, $customQuestion = null)
    {
        try {
            $class = \App\Models\Classes::with('enrollments.user')->findOrFail($classId);
            $students = $class->enrollments;
            
            if ($students->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'class_name' => $class->nama_kelas,
                        'analysis' => 'Belum ada siswa yang terdaftar di kelas ini.',
                        'metrics' => [
                            'total_students' => 0,
                            'avg_score' => 0,
                            'completion_rate' => 0,
                            'on_time_rate' => 0
                        ]
                    ]
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }

            $analysisService = new AIAnalysisService();
            $totalScore = 0;
            $totalCompletionRate = 0;
            $totalOnTimeRate = 0;
            $studentCount = 0;
            $analysisTexts = [];

            foreach ($students as $enrollment) {
                try {
                    $studentAnalysis = $analysisService->analyzeStudentPerformance($enrollment->user->id_user, $classId);
                    $totalScore += $studentAnalysis['metrics']['avg_score'] ?? 0;
                    $totalCompletionRate += $studentAnalysis['metrics']['completion_rate'] ?? 0;
                    $totalOnTimeRate += $studentAnalysis['metrics']['on_time_rate'] ?? 0;
                    $studentCount++;
                } catch (\Exception $e) {
                    Log::warning('Failed to analyze student: ' . $e->getMessage());
                }
            }

            $avgScore = $studentCount > 0 ? round($totalScore / $studentCount, 1) : 0;
            $avgCompletionRate = $studentCount > 0 ? round($totalCompletionRate / $studentCount, 1) : 0;
            $avgOnTimeRate = $studentCount > 0 ? round($totalOnTimeRate / $studentCount, 1) : 0;

            $classAnalysis = "Kelas {$class->nama_kelas} memiliki {$studentCount} siswa aktif. ";
            $classAnalysis .= "Rata-rata nilai kelas adalah {$avgScore}. ";
            $classAnalysis .= "Tingkat penyelesaian tugas mencapai {$avgCompletionRate}% dengan ketepatan waktu {$avgOnTimeRate}%.";

            $result = [
                'class_name' => $class->nama_kelas,
                'analysis' => $classAnalysis,
                'metrics' => [
                    'total_students' => $studentCount,
                    'avg_score' => $avgScore,
                    'completion_rate' => $avgCompletionRate,
                    'on_time_rate' => $avgOnTimeRate
                ]
            ];

            if (!empty($customQuestion)) {
                $context = "Konteks Kelas:\n";
                $context .= "Nama Kelas: {$class->nama_kelas}\n";
                $context .= "Jumlah Siswa: {$studentCount}\n";
                $context .= "Rata-rata Nilai: {$avgScore}\n";
                $context .= "Completion Rate: {$avgCompletionRate}%\n";
                $context .= "On-Time Rate: {$avgOnTimeRate}%\n";
                $context .= "\nAnalisis: {$classAnalysis}\n";
                $context .= "\nPertanyaan: {$customQuestion}\n\nJawab pertanyaan di atas berdasarkan data kelas dengan SINGKAT dan LANGSUNG. Maksimal 2-3 kalimat.";
                
                $answer = $this->ai->chat($context);
                
                $result['custom_answer'] = [
                    'answer' => $answer,
                    'is_student_related' => false
                ];
                
                // Save question and answer to database for class analysis
                $this->saveQuestionToDatabase(0, $classId, $customQuestion, $answer);
            }

            return response()->json([
                'success' => true,
                'data' => $result
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            Log::error('Class analysis error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menganalisis kelas: ' . $e->getMessage()
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
    
    private function answerCustomQuestion($question, $analysis, $userId, $classId)
    {
        try {
            // Get student and class info
            $user = \App\Models\User::find($userId);
            $class = \App\Models\Classes::find($classId);
            
            // Check if question needs detailed analysis or simple answer
            $needsDetailedAnalysis = $this->needsDetailedAnalysis($question);
            
            // Always build context with student data
            $context = "Konteks Siswa:\n";
            $context .= "Nama: {$user->nama}\n";
            $context .= "Kelas: {$class->nama_kelas}\n";
            $context .= "Rata-rata Nilai: {$analysis['metrics']['avg_score']}\n";
            $context .= "Completion Rate: {$analysis['metrics']['completion_rate']}%\n";
            $context .= "On-Time Rate: {$analysis['metrics']['on_time_rate']}%\n";
            $context .= "Trend: {$analysis['metrics']['trend']}\n";
            $context .= "Consistency: {$analysis['metrics']['consistency']}\n";
            $context .= "\nAnalisis: " . (is_array($analysis['analysis']) ? implode(' ', $analysis['analysis']) : $analysis['analysis']);
            
            if ($needsDetailedAnalysis) {
                $context .= "\n\nPertanyaan: {$question}\n\nJawab pertanyaan di atas berdasarkan konteks siswa dengan detail dan lengkap.";
            } else {
                $context .= "\n\nPertanyaan: {$question}\n\nJawab pertanyaan di atas dengan SINGKAT dan LANGSUNG berdasarkan data siswa di atas. Maksimal 2-3 kalimat.";
            }
            
            // Use HuggingFace AI to answer
            $answer = $this->ai->chat($context);
            
            return [
                'answer' => $answer,
                'is_student_related' => !$needsDetailedAnalysis // If simple question, hide metrics
            ];
        } catch (\Exception $e) {
            Log::error('Error answering custom question: ' . $e->getMessage());
            return [
                'answer' => 'Maaf, saya tidak dapat menjawab pertanyaan tersebut saat ini.',
                'is_student_related' => false
            ];
        }
    }
    
    private function needsDetailedAnalysis($question)
    {
        // Keywords that indicate detailed analysis is needed
        $detailedKeywords = [
            'bagaimana performa', 'bagaimana prestasi',
            'analisis', 'evaluasi', 'assessment',
            'tingkat kemampuan', 'level',
            'perkembangan', 'progress detail',
            'rata-rata nilai', 'completion rate', 'on-time rate',
            'trend', 'konsistensi'
        ];
        
        $lowerQuestion = strtolower($question);
        
        foreach ($detailedKeywords as $keyword) {
            if (strpos($lowerQuestion, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    private function saveQuestionToDatabase($userId, $classId, $question, $answer)
    {
        try {
            Log::info('saveQuestionToDatabase called', [
                'user_id' => $userId,
                'class_id' => $classId,
                'question' => substr($question, 0, 100),
                'answer' => substr($answer, 0, 100)
            ]);
            
            // Find or create a submission to attach the question
            $submission = Submission::where('id_user', $userId)
                ->whereHas('assignment', function ($q) use ($classId) {
                    $q->where('id_class', $classId);
                })
                ->orderBy('submitted_at', 'desc')
                ->first();
            
            Log::info('Submission search result', [
                'found' => $submission ? 'yes' : 'no',
                'submission_id' => $submission ? $submission->id_submission : null
            ]);
            
            if (!$submission) {
                // Create dummy submission if none exists
                $assignment = Assignment::where('id_class', $classId)->first();
                Log::info('Assignment search for dummy submission', [
                    'found' => $assignment ? 'yes' : 'no',
                    'assignment_id' => $assignment ? $assignment->id_assignment : null
                ]);
                
                if ($assignment) {
                    $submission = Submission::create([
                        'id_assignment' => $assignment->id_assignment,
                        'id_user' => $userId,
                        'jawaban' => json_encode([]),
                        'status' => 'draft',
                        'score' => 0,
                        'submitted_at' => null,
                    ]);
                    Log::info('Dummy submission created', ['submission_id' => $submission->id_submission]);
                }
            }
            
            if ($submission) {
                // Check if feedback already exists
                $existingFeedback = \App\Models\FeedbackAI::where('id_submission', $submission->id_submission)->first();
                
                if ($existingFeedback) {
                    // Only update question and answer, keep other fields intact
                    $existingFeedback->question = $question;
                    $existingFeedback->answer = $answer;
                    $existingFeedback->save();
                    
                    Log::info('Feedback updated successfully', [
                        'feedback_id' => $existingFeedback->id_feedback,
                        'submission_id' => $submission->id_submission,
                        'question_length' => strlen($question),
                        'answer_length' => strlen($answer)
                    ]);
                } else {
                    // Create new feedback
                    $feedback = \App\Models\FeedbackAI::create([
                        'id_submission' => $submission->id_submission,
                        'feedback_text' => 'AI Analysis',
                        'saran' => 'Pertanyaan AI telah dijawab',
                        'question' => $question,
                        'answer' => $answer,
                    ]);
                    
                    Log::info('Feedback created successfully', [
                        'feedback_id' => $feedback->id_feedback,
                        'submission_id' => $submission->id_submission,
                        'question_length' => strlen($question),
                        'answer_length' => strlen($answer)
                    ]);
                }
            } else {
                Log::warning('No submission found or created, cannot save question');
            }
        } catch (\Exception $e) {
            Log::error('Error saving question to database: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    private function parseAnalysisFromFeedback($feedback)
    {
        $profile = $this->parseFeedbackProfile($feedback->feedback_text);

        $result = [
            'class_name' => $profile['subject'],
            'analysis' => $feedback->saran,
            'metrics' => [
                'max_score' => $profile['last_scores'],
                'avg_score' => $profile['avg_score'],
                'completed' => 0,
                'total_assignments' => 0,
                'completion_rate' => $this->extractMetric($feedback->feedback_text, 'Completion Rate'),
                'on_time_rate' => $this->extractMetric($feedback->feedback_text, 'On-Time Rate'),
                'trend' => $this->extractMetric($feedback->feedback_text, 'Trend', 'stabil'),
                'consistency' => $this->extractMetric($feedback->feedback_text, 'Consistency', 'Sangat Konsisten')
            ]
        ];

        return $result;
    }

    private function extractMetric($text, $label, $default = 0)
    {
        if (preg_match('/' . preg_quote($label, '/') . ':\s*([^\n]+)/i', $text, $m)) {
            $value = trim($m[1]);
            // Remove % sign if present
            $value = str_replace('%', '', $value);
            // Return as is if not numeric (like 'stabil', 'meningkat')
            return is_numeric($value) ? (float) $value : $value;
        }
        return $default;
    }

    // Siswa: Rekomendasi materi
    public function getRecommendations(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak valid'
            ], 400);
        }

        $classIds = $user->enrollments()->pluck('id_class')->toArray();
        if (empty($classIds)) {
            return $this->noClassResponse();
        }

        // Check if refresh is requested
        if ($request->has('refresh') && $request->refresh == '1') {
            // Clear cache
            CacheService::clearRecommendationsCache($user->id_user);

            // Delete existing feedback to force regeneration
            \App\Models\FeedbackAI::whereHas('submission', function ($q) use ($user, $classIds) {
                $q->where('id_user', $user->id_user)
                    ->whereHas('assignment', function ($subQ) use ($classIds) {
                        $subQ->whereIn('id_class', $classIds);
                    });
            })->delete();

            Log::info('Recommendations cache cleared and feedback deleted', [
                'user_id' => $user->id_user,
                'class_ids' => $classIds
            ]);
        }

        // Try to get from cache first (if not refreshing)
        return CacheService::getOrGenerateRecommendations($user->id_user, $classIds, function () use ($user, $classIds) {
            $latestFeedback = $this->getLatestFeedback($user, $classIds);
            if ($latestFeedback) {
                return $this->buildFeedbackResponse($latestFeedback, $classIds, true);
            }

            return $this->buildFallbackResponse($user, $classIds);
        });
    }

    private function noClassResponse()
    {
        return response()->json([
            'success' => true,
            'recommendations' => 'Bergabunglah dengan kelas terlebih dahulu untuk mendapatkan rekomendasi pembelajaran yang personal.',
            'profile' => [
                'subject' => 'Belum ada',
                'last_scores' => 'Belum ada',
                'weak_topics' => 'Belum ada',
                'learning_style' => 'Belum diketahui'
            ]
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    private function getLatestFeedback($user, $classIds)
    {
        return \App\Models\FeedbackAI::whereHas('submission', function ($q) use ($user, $classIds) {
            $q->where('id_user', $user->id_user)
                ->whereHas('assignment', function ($subQ) use ($classIds) {
                    $subQ->whereIn('id_class', $classIds);
                });
        })
            ->orderBy('created_at', 'desc')
            ->first();
    }

    private function buildFeedbackResponse($feedback, $classIds, $fromDatabase)
    {
        $profile = $this->parseFeedbackProfile($feedback->feedback_text);

        Log::info('Building feedback response from database');

        // Get materials for links
        $materials = CacheService::getOrFetchMaterials($classIds, function () use ($classIds) {
            return \App\Models\Material::whereIn('id_class', $classIds)->get();
        });

        // Generate recommendations from AIAnalysisService
        $analysisService = new AIAnalysisService();
        $allRecommendations = [];
        
        foreach ($classIds as $classId) {
            try {
                $userId = $feedback->submission->id_user;
                $fullAnalysis = $analysisService->analyzeStudentPerformance($userId, $classId);
                if (isset($fullAnalysis['recommendations']) && is_array($fullAnalysis['recommendations'])) {
                    foreach ($fullAnalysis['recommendations'] as $rec) {
                        $allRecommendations[] = [
                            'title' => $rec['title'] ?? 'Rekomendasi',
                            'description' => $rec['description'] ?? '',
                            'resources' => 'Tutorial | Video | Dokumentasi'
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error getting analysis for class: ' . $e->getMessage());
            }
        }

        $formattedRecommendations = $this->formatRecommendationsWithLinks($allRecommendations, $materials);

        return response()->json([
            'success' => true,
            'recommendations' => $formattedRecommendations,
            'profile' => $profile,
            'from_database' => $fromDatabase
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    private function buildFallbackResponse($user, $classIds)
    {
        $submissions = $this->getUserSubmissions($user, $classIds);
        
        // Check if feedback already exists for any class
        $existingFeedback = $this->getLatestFeedback($user, $classIds);
        if ($existingFeedback) {
            return $this->buildFeedbackResponse($existingFeedback, $classIds, true);
        }

        // Get materials from cache
        $materials = CacheService::getOrFetchMaterials($classIds, function () use ($classIds) {
            return \App\Models\Material::whereIn('id_class', $classIds)->get();
        });

        // Use AIAnalysisService for consistent recommendations
        $analysisService = new AIAnalysisService();
        $profile = $this->buildStudentProfile($submissions, $classIds);
        
        // Get full analysis with recommendations for each class
        $allRecommendations = [];
        foreach ($classIds as $classId) {
            try {
                $fullAnalysis = $analysisService->analyzeStudentPerformance($user->id_user, $classId);
                if (isset($fullAnalysis['recommendations']) && is_array($fullAnalysis['recommendations'])) {
                    foreach ($fullAnalysis['recommendations'] as $rec) {
                        $allRecommendations[] = [
                            'title' => $rec['title'] ?? 'Rekomendasi',
                            'description' => $rec['description'] ?? '',
                            'resources' => 'Tutorial | Video | Dokumentasi'
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error getting analysis for class: ' . $e->getMessage());
            }
        }
        
        // If no recommendations from analysis, use AI as fallback
        if (empty($allRecommendations)) {
            $aiRecommendations = $this->ai->recommendMaterials($profile);
            $allRecommendations = $this->parseAIRecommendations($aiRecommendations);
        }

        Log::info('Generated recommendations', [
            'user_id' => $user->id_user,
            'recommendations_count' => count($allRecommendations)
        ]);

        // Save feedback to database for each class with full analysis
        if ($submissions->count() > 0) {
            foreach ($classIds as $classId) {
                $this->saveFallbackFeedback($user->id_user, $classId, $allRecommendations);
            }
        } else {
            // If no submissions yet, create a dummy feedback entry
            Log::info('No submissions found, creating dummy feedback', ['user_id' => $user->id_user]);

            // Get the first assignment from any class
            $firstAssignment = Assignment::whereIn('id_class', $classIds)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($firstAssignment) {
                // Create a dummy submission to attach feedback
                $dummySubmission = Submission::create([
                    'id_assignment' => $firstAssignment->id_assignment,
                    'id_user' => $user->id_user,
                    'jawaban' => json_encode([]),
                    'status' => 'draft',
                    'score' => 0,
                    'submitted_at' => null,
                ]);

                $feedbackText = "Profil & Progress Belajar\n";
                $feedbackText .= "========================\n\n";
                $feedbackText .= "Mata Pelajaran: " . ($profile['subject'] ?? 'Umum') . "\n";
                $feedbackText .= "Nilai Terakhir: " . ($profile['last_scores'] ?? '-') . "\n";
                $feedbackText .= "Rata-rata Nilai: " . ($profile['avg_score'] ?? 0) . "\n";
                $feedbackText .= "Progress Belajar: " . ($profile['progress'] ?? '-') . "\n";
                $feedbackText .= "Completion Rate: 0%\n";
                $feedbackText .= "On-Time Rate: 0%\n";
                $feedbackText .= "Trend: stabil\n";
                $feedbackText .= "Consistency: Sangat Konsisten\n";
                $feedbackText .= "Status Performa: " . ($profile['performance_status'] ?? 'Cukup') . "\n";

                \App\Models\FeedbackAI::create([
                    'id_submission' => $dummySubmission->id_submission,
                    'feedback_text' => $feedbackText,
                    'saran' => 'Mulai kerjakan tugas untuk mendapatkan feedback yang lebih personal dari AI.',
                    'created_at' => now(),
                ]);

                Log::info('Dummy feedback created', [
                    'user_id' => $user->id_user,
                    'submission_id' => $dummySubmission->id_submission
                ]);
            }
        }

        $recommendations = $this->formatRecommendationsWithLinks($allRecommendations, $materials);

        return response()->json([
            'success' => true,
            'recommendations' => $recommendations,
            'profile' => $profile,
            'from_database' => false
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    private function saveFallbackFeedback($userId, $classId, $recommendations)
    {
        try {
            // Get full analysis data
            $analysisService = new AIAnalysisService();
            $fullAnalysis = $analysisService->analyzeStudentPerformance($userId, $classId);
            
            $submissions = Submission::where('id_user', $userId)
                ->whereHas('assignment', function ($q) use ($classId) {
                    $q->where('id_class', $classId);
                })
                ->where(function ($query) {
                    $query->where('status', 'graded')
                        ->orWhere(function ($q) {
                            $q->where('status', 'submitted')
                                ->whereHas('assignment', function ($subQ) {
                                    $subQ->where('tipe', 'pilihan_ganda');
                                });
                        });
                })
                ->orderBy('submitted_at', 'desc')
                ->limit(5)
                ->get();

            foreach ($submissions as $submission) {
                $existingFeedback = \App\Models\FeedbackAI::where('id_submission', $submission->id_submission)->first();
                if ($existingFeedback) {
                    continue;
                }

                $metrics = $fullAnalysis['metrics'];
                $feedbackText = "Profil & Progress Belajar\n";
                $feedbackText .= "========================\n\n";
                $feedbackText .= "Mata Pelajaran: {$fullAnalysis['class_name']}\n";
                $feedbackText .= "Nilai Terakhir: {$metrics['max_score']}\n";
                $feedbackText .= "Rata-rata Nilai: {$metrics['avg_score']}\n";
                $feedbackText .= "Progress Belajar: {$metrics['completed']} dari {$metrics['total_assignments']} tugas\n";
                $feedbackText .= "Completion Rate: {$metrics['completion_rate']}%\n";
                $feedbackText .= "On-Time Rate: {$metrics['on_time_rate']}%\n";
                $feedbackText .= "Trend: {$metrics['trend']}\n";
                $feedbackText .= "Consistency: {$metrics['consistency']}\n";

                // Ensure recommendations is properly encoded as JSON
                $saran = implode("\n\n", $fullAnalysis['analysis']);

                \App\Models\FeedbackAI::create([
                    'id_submission' => $submission->id_submission,
                    'feedback_text' => $feedbackText,
                    'saran' => $saran,
                    'created_at' => now(),
                ]);

                Log::info('Feedback saved for submission', [
                    'submission_id' => $submission->id_submission,
                    'user_id' => $userId,
                    'class_id' => $classId
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error saving fallback feedback: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
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

    private function getUserSubmissions($user, $classIds)
    {
        return Submission::where('id_user', $user->id_user)
            ->whereHas('assignment', function ($q) use ($classIds) {
                $q->whereIn('id_class', $classIds);
            })
            ->with('assignment.class')
            ->orderBy('submitted_at', 'desc')
            ->take(5)
            ->get();
    }

    private function buildStudentProfile($submissions, $classIds)
    {
        $avgScore = $submissions->avg('score') ?? 0;
        $totalSubmissions = $submissions->count();
        $completedCount = $submissions->where('status', 'graded')->count();
        
        $materials = \App\Models\Material::whereIn('id_class', $classIds)
            ->with('class')
            ->get();
        
        $availableMaterials = $materials->map(fn($m) => $m->judul . ' (' . $m->class->nama_kelas . ')')
            ->implode(', ');

        return [
            'subject' => $submissions->first()?->assignment?->class?->nama_kelas ?? 'Umum',
            'last_scores' => $submissions->pluck('score')->filter()->implode(', ') ?: 'Belum ada',
            'avg_score' => round($avgScore, 1),
            'progress' => "{$completedCount} dari {$totalSubmissions} tugas",
            'performance_status' => $this->getPerformanceStatus($avgScore),
            'weak_topics' => $submissions->filter(fn($sub) => $sub->score < 70)->pluck('assignment.judul')->take(3)->implode(', ') ?: 'Tidak ada',
            'learning_style' => $avgScore >= 80 ? 'Visual & Praktik' : 'Perlu Penguatan Dasar',
            'available_materials' => $availableMaterials ?: 'Belum ada materi'
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

    private function parseFeedbackProfile($feedbackText)
    {
        $profile = [
            'subject' => 'Umum',
            'last_scores' => '-',
            'avg_score' => 0,
            'progress' => '-',
            'performance_status' => 'Cukup'
        ];

        if (preg_match('/Mata Pelajaran:\s*(.+?)(?:\n|$)/i', $feedbackText, $m)) {
            $profile['subject'] = trim($m[1]);
        }
        if (preg_match('/Nilai Terakhir:\s*(.+?)(?:\n|$)/i', $feedbackText, $m)) {
            $profile['last_scores'] = trim($m[1]);
        }
        if (preg_match('/Rata-rata Nilai:\s*(.+?)(?:\n|$)/i', $feedbackText, $m)) {
            $avgVal = (float) trim($m[1]);
            $profile['avg_score'] = $avgVal;
        }
        if (preg_match('/Progress Belajar:\s*(.+?)(?:\n|$)/i', $feedbackText, $m)) {
            $profile['progress'] = trim($m[1]);
        }
        if (preg_match('/Trend:\s*(.+?)(?:\n|$)/i', $feedbackText, $m)) {
            $trend = trim($m[1]);
            if ($trend === 'menurun') {
                $profile['performance_status'] = 'Perlu Perhatian Khusus';
            } elseif ($trend === 'meningkat') {
                $profile['performance_status'] = 'Baik';
            } else {
                $profile['performance_status'] = 'Cukup';
            }
        } elseif ($profile['avg_score'] > 0) {
            if ($profile['avg_score'] < 60) {
                $profile['performance_status'] = 'Perlu Perhatian Khusus';
            } elseif ($profile['avg_score'] >= 80) {
                $profile['performance_status'] = 'Baik';
            } else {
                $profile['performance_status'] = 'Cukup';
            }
        }

        return $profile;
    }

    private function formatRecommendationsWithLinks($recommendationData, $materials)
    {
        try {
            // Decode JSON if it's a string
            if (is_string($recommendationData)) {
                $recommendations = json_decode($recommendationData, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('JSON decode error: ' . json_last_error_msg());
                    return '<p class="text-red-600">Format rekomendasi tidak valid</p>';
                }
            } else {
                $recommendations = $recommendationData;
            }

            if (!is_array($recommendations)) {
                return '<p class="text-gray-700">Tidak ada rekomendasi tersedia</p>';
            }

            $html = "";
            $counter = 1;

            foreach ($recommendations as $rec) {
                $title = $rec['title'] ?? '';
                $description = $rec['description'] ?? '';
                $resources = $rec['resources'] ?? '';

                // Generate search links
                $youtubeLink = "https://www.youtube.com/results?search_query=" . urlencode($title . " tutorial");
                $googleLink = "https://www.google.com/search?q=" . urlencode($title);
                $onlineLink = '';

                // Search through pre-loaded materials
                if (is_array($materials) || $materials instanceof \Illuminate\Support\Collection) {
                    foreach ($materials as $material) {
                        if (stripos($title, $material->judul) !== false || stripos($material->judul, $title) !== false) {
                            if (isset($material->online_link) && $material->online_link) {
                                $onlineLink = $material->online_link;
                            }
                            break;
                        }
                    }
                }

                $html .= "<div class='mb-4 p-5 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border-2 border-indigo-200 shadow-sm hover:shadow-md transition-shadow'>";
                $html .= "<div class='flex items-start gap-3 mb-3'>";
                $html .= "<div class='flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm'>{$counter}</div>";
                $html .= "<div class='flex-1'>";
                $html .= "<h3 class='font-bold text-gray-900 text-lg mb-2'>" . htmlspecialchars($title) . "</h3>";
                $html .= "<p class='text-sm text-gray-700 leading-relaxed'>" . htmlspecialchars($description) . "</p>";
                $html .= "</div>";
                $html .= "</div>";

                if (!empty($resources)) {
                    $html .= "<div class='flex items-center gap-2 mb-3 text-xs text-gray-600 bg-white/50 px-3 py-2 rounded-lg'>";
                    $html .= "<svg class='w-4 h-4 text-indigo-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'/></svg>";
                    $html .= "<span><strong>Sumber Belajar:</strong> " . htmlspecialchars($resources) . "</span>";
                    $html .= "</div>";
                }

                // Action buttons
                $html .= "<div class='flex flex-wrap gap-2'>";

                if ($onlineLink) {
                    $html .= "<a href='" . htmlspecialchars($onlineLink) . "' target='_blank' class='inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-xs font-semibold rounded-lg transition-all shadow-md hover:shadow-lg'>";
                    $html .= "<svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14'/></svg>";
                    $html .= "Akses Materi";
                    $html .= "</a>";
                }

                $html .= "<a href='" . htmlspecialchars($youtubeLink) . "' target='_blank' class='inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white text-xs font-semibold rounded-lg transition-all shadow-md hover:shadow-lg'>";
                $html .= "<svg class='w-4 h-4' fill='currentColor' viewBox='0 0 24 24'><path d='M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z'/></svg>";
                $html .= "Video Tutorial";
                $html .= "</a>";

                $html .= "<a href='" . htmlspecialchars($googleLink) . "' target='_blank' class='inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white text-xs font-semibold rounded-lg transition-all shadow-md hover:shadow-lg'>";
                $html .= "<svg class='w-4 h-4' fill='currentColor' viewBox='0 0 24 24'><path d='M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z'/></svg>";
                $html .= "Cari di Google";
                $html .= "</a>";

                $html .= "</div>";
                $html .= "</div>";
                $counter++;
            }

            return $html;
        } catch (\Exception $e) {
            Log::error('Error formatting recommendations: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return '<p class="text-red-600">Gagal memformat rekomendasi. Silakan coba lagi.</p>';
        }
    }

    // Guru: Koreksi otomatis jawaban siswa
    public function autoGrade(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|exists:submissions,id_submission'
        ]);
        $submission = Submission::with('assignment.questions')->findOrFail($request->submission_id);
        $assignment = $submission->assignment;
        // Only for essay/praktik
        if (!in_array($assignment->tipe, ['essay', 'praktik'])) {
            return response()->json([
                'success' => false,
                'message' => 'AI grading hanya untuk soal essay/praktik'
            ], 400);
        }
        $answers = json_decode($submission->jawaban, true);
        $totalScore = 0;
        $feedbacks = [];
        $questionNum = 1;
        foreach ($answers as $questionId => $studentAnswer) {
            $question = $assignment->questions->where('id_question', $questionId)->first();
            if (!$question || !$question->kunci_jawaban) {
                continue;
            }
            $result = $this->ai->gradeAnswer(
                $question->soal,
                $question->kunci_jawaban,
                $studentAnswer,
                $question->poin
            );
            $totalScore += $result['score'];
            $feedbacks[] = "Soal {$questionNum}: {$result['feedback']}";
            $questionNum++;
        }
        $finalFeedback = implode(" | ", $feedbacks);
        return response()->json([
            'success' => true,
            'score' => min($totalScore, $assignment->max_score),
            'feedback' => $finalFeedback,
            'submission_id' => $submission->id_submission
        ]);
    }
}
