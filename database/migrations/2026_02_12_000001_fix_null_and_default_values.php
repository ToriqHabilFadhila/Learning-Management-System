<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Fix token_kelas: set default expires_at (Hanya untuk PostgreSQL)
        DB::table('token_kelas')
            ->whereNull('expires_at')
            ->update([
                'expires_at' => DB::raw("NOW() + INTERVAL '30 days'")
            ]);

        // 2. Fix progress: Pastikan tiap orang yang daftar kelas punya record progress
        // Kita pakai chunk untuk jaga-jaga kalau datanya banyak supaya tidak memory limit
        DB::table('class_enrollments')
            ->select('id_user', 'id_class')
            ->chunkById(100, function ($enrollments) {
                foreach ($enrollments as $enrollment) {
                    DB::table('progress')
                        ->updateOrInsert(
                            [
                                'id_user' => $enrollment->id_user,
                                'id_class' => $enrollment->id_class,
                            ],
                            [
                                'persentase' => 0,
                                'status' => 'in_progress',
                                'last_activity' => now(),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                }
            }, 'id_user', 'id_user'); // Sesuaikan kolom ID jika diperlukan

        // 3. Fix activity_logs: Inisialisasi sistem
        $adminUser = DB::table('users')->orderBy('id_user', 'asc')->first();
        if ($adminUser) {
            DB::table('activity_logs')->insert([
                'id_user' => $adminUser->id_user,
                'action_type' => 'system_init',
                'target_type' => 'system',
                'description' => 'System data integrity verified and initialized',
                'ip_address' => '127.0.0.1',
                'timestamp' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('activity_logs')
            ->where('action_type', 'system_init')
            ->delete();
    }
};
