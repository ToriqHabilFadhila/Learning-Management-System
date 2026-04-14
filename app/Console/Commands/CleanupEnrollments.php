<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClassEnrollment;
use App\Models\Classes;
use Illuminate\Support\Facades\DB;

class CleanupEnrollments extends Command
{
    protected $signature = 'cleanup:enrollments';
    protected $description = 'Membersihkan enrollment yang duplikat atau bermasalah';

    public function handle()
    {
        $this->info('Memulai pembersihan enrollment...');

        // 1. Hapus enrollment untuk kelas yang tidak aktif
        $inactiveEnrollments = ClassEnrollment::whereHas('class', function($q) {
            $q->where('status', '!=', 'active');
        })->count();
        
        ClassEnrollment::whereHas('class', function($q) {
            $q->where('status', '!=', 'active');
        })->delete();
        
        $this->info("Dihapus {$inactiveEnrollments} enrollment untuk kelas tidak aktif");

        // 2. Hapus enrollment duplikat (keep yang terbaru)
        $duplicates = DB::select("
            SELECT id_user, id_class, COUNT(*) as count
            FROM class_enrollments 
            WHERE status = 'active'
            GROUP BY id_user, id_class 
            HAVING COUNT(*) > 1
        ");

        $duplicateCount = 0;
        foreach ($duplicates as $duplicate) {
            // Keep enrollment terbaru, hapus yang lama
            $enrollments = ClassEnrollment::where('id_user', $duplicate->id_user)
                ->where('id_class', $duplicate->id_class)
                ->where('status', 'active')
                ->orderBy('id_enrollment', 'desc')
                ->get();

            // Hapus semua kecuali yang pertama (terbaru)
            for ($i = 1; $i < $enrollments->count(); $i++) {
                $enrollments[$i]->delete();
                $duplicateCount++;
            }
        }

        $this->info("Dihapus {$duplicateCount} enrollment duplikat");

        // 3. Hapus enrollment untuk user yang tidak ada
        $orphanEnrollments = ClassEnrollment::whereDoesntHave('user')->count();
        ClassEnrollment::whereDoesntHave('user')->delete();
        
        $this->info("Dihapus {$orphanEnrollments} enrollment tanpa user");

        // 4. Hapus enrollment untuk kelas yang tidak ada
        $orphanClassEnrollments = ClassEnrollment::whereDoesntHave('class')->count();
        ClassEnrollment::whereDoesntHave('class')->delete();
        
        $this->info("Dihapus {$orphanClassEnrollments} enrollment tanpa kelas");

        $this->info('Pembersihan enrollment selesai!');
        
        return 0;
    }
}