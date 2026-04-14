<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Classes;
use App\Models\ClassEnrollment;
use App\Models\Assignment;
use App\Services\ActivityLogService;
use App\Services\ProgressService;
use App\Services\ValidationService;
use App\Services\SecurityService;

class SiswaController extends Controller
{
    public function index()
    {
        return view('siswa.kelas');
    }

    public function join(Request $request)
    {
        ValidationService::validateToken($request->all());
        $token = \App\Models\TokenKelas::where('token_code', strtoupper($request->token))->first();
        if (!$token) {
            return redirect()->back()->with('error', 'Token tidak valid!');
        }
        // Cek apakah token sudah kadaluarsa
        if ($token->expires_at && now()->greaterThan($token->expires_at)) {
            return redirect()->back()->with('error', 'Token sudah kadaluarsa! Hubungi guru untuk mendapatkan token baru.');
        }
        // Cek apakah token sudah mencapai batas penggunaan
        if ($token->max_uses > 0 && $token->times_used >= $token->max_uses) {
            return redirect()->back()->with('error', 'Token sudah mencapai batas penggunaan maksimal!');
        }
        $kelas = Classes::find($token->id_class);
        if ($kelas->enrollments()->count() >= $kelas->max_students) {
            return redirect()->back()->with('error', 'Kelas sudah penuh!');
        }
        $exists = ClassEnrollment::where('id_class', $kelas->id_class)
            ->where('id_user', Auth::user()->id_user)
            ->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Kamu sudah join kelas ini!');
        }
        ClassEnrollment::create([
            'id_class' => $kelas->id_class,
            'id_user' => Auth::user()->id_user,
            'status' => 'active',
            'token_used' => $token->token_code,
        ]);
        $token->increment('times_used');
        ActivityLogService::logJoinClass($kelas->id_class, $kelas->nama_kelas, $token->token_code);
        ProgressService::updateProgress(Auth::user()->id_user, $kelas->id_class);
        return redirect()->back()->with('success', 'Berhasil join kelas!');
    }

    public function showClass($id)
    {
        $kelas = Classes::with([
            'enrollments.user',
            'creator',
            'assignments' => function($query) {
                $query->where('is_published', true)
                    ->orderBy('deadline', 'asc')
                    ->with(['submissions' => function($q) {
                        $q->where('id_user', Auth::id());
                    }, 'questions.options']);
            },
            'materials.uploader'
        ])->findOrFail($id);
        
        SecurityService::authorizeClassAccess(Auth::user(), $kelas);
        return view('siswa.class-detail', compact('kelas'));
    }

    public function showAssignment($id)
    {
        $assignment = Assignment::with(['class', 'questions.options'])->findOrFail($id);
        SecurityService::authorizeAssignmentAccess(Auth::user(), $assignment);
        return view('siswa.assignment', compact('assignment'));
    }

    public function submitAssignment(Request $request, $id)
    {
        $assignment = Assignment::with('questions.options')->findOrFail($id);
        SecurityService::authorizeAssignmentAccess(Auth::user(), $assignment);
        
        if (SecurityService::isDeadlinePassed($assignment)) {
            return redirect()->back()->with('error', 'Deadline tugas sudah terlewat!');
        }
        
        if ($assignment->tipe === 'praktik') {
            ValidationService::validateSubmission($request->all(), 'praktik');
            $filePath = $request->file('file')->store('submissions', 'public');
            
            $submission = \App\Models\Submission::updateOrCreate(
                ['id_assignment' => $id, 'id_user' => Auth::user()->id_user],
                [
                    'jawaban' => $request->jawaban ?? '',
                    'file_path' => $filePath,
                    'submitted_at' => now(),
                    'status' => 'submitted',
                ]
            );
            
            ActivityLogService::logSubmitAssignment($id, $assignment->judul, 'praktik');
            ProgressService::updateProgress(Auth::user()->id_user, $assignment->id_class);
            
            \App\Models\Notification::create([
                'id_user' => $assignment->created_by,
                'type' => 'feedback',
                'title' => 'Tugas Dikumpulkan!',
                'message' => Auth::user()->nama . " telah mengumpulkan tugas '{$assignment->judul}' dan perlu dinilai.",
                'related_id' => $submission->id_submission,
                'created_at' => now(),
            ]);
        } else {
            ValidationService::validateSubmission($request->all(), $assignment->tipe);
            $score = 0;
            $status = 'submitted';
            if ($assignment->tipe === 'pilihan_ganda') {
                foreach ($request->answers as $questionId => $answerId) {
                    $question = $assignment->questions->where('id_question', $questionId)->first();
                    if ($question) {
                        $selectedOption = $question->options->where('id_option', $answerId)->first();
                        if ($selectedOption && $selectedOption->is_correct) {
                            $score += $question->poin;
                        }
                    }
                }
                $status = 'graded';
            }
            
            $submission = \App\Models\Submission::updateOrCreate(
                ['id_assignment' => $id, 'id_user' => Auth::user()->id_user],
                [
                    'jawaban' => json_encode($request->answers),
                    'submitted_at' => now(),
                    'score' => $assignment->tipe === 'pilihan_ganda' ? $score : null,
                    'status' => $status,
                    'graded_by' => $assignment->tipe === 'pilihan_ganda' ? 1 : null,
                    'graded_at' => $assignment->tipe === 'pilihan_ganda' ? now() : null,
                ]
            );
            
            ActivityLogService::logSubmitAssignment($id, $assignment->judul, $assignment->tipe);
            ProgressService::updateProgress(Auth::user()->id_user, $assignment->id_class);
            
            if ($assignment->tipe === 'essay') {
                \App\Models\Notification::create([
                    'id_user' => $assignment->created_by,
                    'type' => 'feedback',
                    'title' => 'Tugas Essay Dikumpulkan!',
                    'message' => Auth::user()->nama . " telah mengumpulkan tugas essay '{$assignment->judul}' dan perlu dinilai.",
                    'related_id' => $submission->id_submission,
                    'created_at' => now(),
                ]);
            }
        }
        return redirect()->route('dashboard')->with('success', 'Jawaban berhasil dikumpulkan!');
    }

    public function showSubmission($id)
    {
        $submission = \App\Models\Submission::with(['assignment.class', 'assignment.questions.options'])->findOrFail($id);
        SecurityService::authorizeSubmissionAccess(Auth::user(), $submission);
        return view('siswa.submission-detail', compact('submission'));
    }

    public function materials()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $classIds = $user->enrollments()->pluck('id_class')->toArray();
        $materials = \App\Models\Material::whereIn('id_class', $classIds)
            ->with(['class', 'uploader'])
            ->orderBy('id_material', 'desc')
            ->get();
        return view('siswa.materials', compact('materials'));
    }

    public function getTokenInfo($token)
    {
        // Validate token format
        if (!preg_match('/^[A-Z0-9]{8}$/', strtoupper($token))) {
            return response()->json(['valid' => false, 'message' => 'Format token tidak valid'], 400);
        }
        
        $tokenRecord = \App\Models\TokenKelas::where('token_code', strtoupper($token))->first();
        if (!$tokenRecord) {
            return response()->json(['valid' => false, 'message' => 'Token tidak ditemukan'], 404);
        }
        $isExpired = $tokenRecord->expires_at && now()->greaterThan($tokenRecord->expires_at);
        $isMaxUsesReached = $tokenRecord->max_uses > 0 && $tokenRecord->times_used >= $tokenRecord->max_uses;
        $isValid = !$isExpired && !$isMaxUsesReached;
        return response()->json([
            'valid' => $isValid,
            'token_code' => $tokenRecord->token_code,
            'class_name' => $tokenRecord->kelas->nama_kelas,
            'expires_at' => $tokenRecord->expires_at?->format('d M Y H:i'),
            'times_used' => $tokenRecord->times_used,
            'max_uses' => $tokenRecord->max_uses,
            'is_expired' => $isExpired,
            'is_max_uses_reached' => $isMaxUsesReached,
            'message' => $isExpired ? 'Token sudah kadaluarsa' : ($isMaxUsesReached ? 'Token sudah mencapai batas penggunaan' : 'Token valid'),
        ]);
    }

    public function getProgress()
    {
        if (!SecurityService::isSiswa(Auth::user())) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $summary = ProgressService::getProgressSummary(Auth::user()->id_user);
        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }
}
