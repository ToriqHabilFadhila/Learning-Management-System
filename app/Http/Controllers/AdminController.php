<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Classes;
use App\Models\Assignment;
use App\Models\Material;
use App\Models\Submission;
use App\Models\ClassEnrollment;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_guru' => User::where('role', 'guru')->count(),
            'total_siswa' => User::where('role', 'siswa')->count(),
            'total_classes' => Classes::count(),
            'total_assignments' => Assignment::count(),
            'total_materials' => Material::count(),
            'active_today' => User::whereDate('last_login', today())->count(),
        ];
        // Recent Activities
        $activities = $this->getRecentActivities();
        // Progress by Subject
        $progressBySubject = $this->getProgressBySubject();
        // Top Classes
        $topClasses = Classes::withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(3)
            ->get();
        $users = User::latest()->get();
        return view('dashboard.admin', compact(
            'stats',
            'activities',
            'progressBySubject',
            'topClasses',
            'users'
        ));
    }

    private function getRecentActivities()
    {
        $activities = [];
        // New users
        $newUsers = User::where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();
        foreach ($newUsers as $user) {
            $activities[] = [
                'type' => 'user',
                'icon' => 'user-add',
                'color' => 'green',
                'title' => 'User Baru Terdaftar',
                'description' => $user->nama . ' mendaftar sebagai ' . $user->role,
                'time' => $user->created_at->diffForHumans(),
            ];
        }
        // New classes
        $newClasses = Classes::with('creator')
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();
        foreach ($newClasses as $class) {
            $activities[] = [
                'type' => 'class',
                'icon' => 'class',
                'color' => 'purple',
                'title' => 'Kelas Baru Dibuat',
                'description' => ($class->creator->nama ?? 'Guru') . ' membuat "' . $class->nama_kelas . '"',
                'time' => $class->created_at->diffForHumans(),
            ];
        }
        // New materials
        $newMaterials = Material::with(['creator', 'class'])
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();
        foreach ($newMaterials as $material) {
            $activities[] = [
                'type' => 'material',
                'icon' => 'upload',
                'color' => 'blue',
                'title' => 'Materi Baru Diupload',
                'description' => ($material->creator->nama ?? 'Guru') . ' mengupload materi "' . $material->judul . '"',
                'time' => $material->created_at->diffForHumans(),
            ];
        }
        // New assignments
        $newAssignments = Assignment::with(['creator', 'class'])
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();
        foreach ($newAssignments as $assignment) {
            $activities[] = [
                'type' => 'assignment',
                'icon' => 'assignment',
                'color' => 'orange',
                'title' => 'Tugas Baru Dibuat',
                'description' => ($assignment->creator->nama ?? 'Guru') . ' membuat tugas "' . $assignment->judul . '"',
                'time' => $assignment->created_at->diffForHumans(),
            ];
        }
        // Sort by time and take 4 most recent
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        return array_slice($activities, 0, 4);
    }

    public function getUsers(Request $request)
    {
        $query = User::query();
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $users = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function($user) {
                return [
                    'id_user' => $user->id_user,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'role' => $user->role
                ];
            });
        return response()->json($users);
    }

    public function storeUser(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'role' => 'required|in:guru,siswa',
            ]);
            
            User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_active' => true,
                'is_verified' => true,
            ]);
            
            return redirect()->route('dashboard')->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',id_user',
            'role' => 'required|in:guru,siswa,admin',
        ]);
        $user->update($validated);
        if ($request->has('password') && $request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }
        return response()->json(['success' => true, 'user' => $user]);
    }

    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            if ($user->role === 'admin') {
                return redirect()->route('dashboard')->with('error', 'Tidak dapat menghapus user admin!');
            }
            $user->delete();
            return redirect()->route('dashboard')->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Gagal menghapus user!');
        }
    }

    public function getClasses(Request $request)
    {
        $classes = Classes::with(['creator', 'enrollments', 'activeToken'])
            ->withCount('enrollments')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($class) {
                return [
                    'id_class' => $class->id_class,
                    'nama_kelas' => $class->nama_kelas,
                    'deskripsi' => $class->deskripsi,
                    'max_students' => $class->max_students,
                    'enrollments_count' => $class->enrollments_count,
                    'creator' => [
                        'nama' => $class->creator->nama ?? 'Unknown'
                    ],
                    'active_token' => [
                        'token_code' => $class->activeToken->token_code ?? '-'
                    ]
                ];
            });
        return response()->json($classes);
    }

    public function deleteClass($id)
    {
        try {
            $class = Classes::findOrFail($id);
            $class->delete();
            return redirect()->route('dashboard')->with('success', 'Kelas berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Gagal menghapus kelas!');
        }
    }

    public function getMonitoring()
    {
        $data = [
            'total_assignments' => Assignment::count(),
            'avg_completion' => $this->calculateAvgCompletion(),
            'total_materials' => Material::count(),
            'active_today' => User::whereDate('last_login', today())->count(),
            'progress_by_subject' => $this->getProgressBySubject(),
        ];
        return response()->json($data);
    }

    private function calculateAvgCompletion()
    {
        $totalAssignments = Assignment::count();
        if ($totalAssignments == 0) return 0;
        $completedSubmissions = Submission::where('status', 'graded')->count();
        $totalEnrollments = ClassEnrollment::count();
        if ($totalEnrollments == 0) return 0;
        return round(($completedSubmissions / ($totalAssignments * $totalEnrollments)) * 100);
    }

    private function getProgressBySubject()
    {
        $classes = Classes::withCount(['assignments', 'enrollments'])->get();
        $progress = [];
        foreach ($classes as $class) {
            $subject = $class->nama_kelas;
            $totalPossible = $class->assignments_count * $class->enrollments_count;
            if ($totalPossible > 0) {
                $completed = Submission::whereHas('assignment', function($q) use ($class) {
                    $q->where('id_class', $class->id_class);
                })->where('status', 'graded')->count();
                $percentage = round(($completed / $totalPossible) * 100);
                $progress[] = [
                    'subject' => $subject,
                    'progress' => $percentage
                ];
            }
        }
        return $progress;
    }
}
