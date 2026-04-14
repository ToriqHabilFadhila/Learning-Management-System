<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fix token_kelas: set default expires_at to 30 days from now
        DB::table('token_kelas')
            ->whereNull('expires_at')
            ->update([
                'expires_at' => DB::raw("NOW() + INTERVAL '30 days'")
            ]);

        // Fix token_kelas: set max_uses to unlimited (0) if it's 0
        // This is already correct, but ensure consistency
        DB::table('token_kelas')
            ->where('max_uses', 0)
            ->update([
                'max_uses' => 0  // 0 means unlimited
            ]);

        // Fix class_enrollments: ensure token_used is tracked
        // This is nullable by design, so no fix needed
        // But we can add a note that it tracks which token was used

        // Fix progress: ensure all enrolled students have progress records
        $enrollments = DB::table('class_enrollments')
            ->select('id_user', 'id_class')
            ->get();

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

        // Fix feedback_ai: ensure it's properly structured
        // This table is for storing AI feedback, so it's okay if empty
        // No fix needed

        // Fix activity_logs: add initial system activity log only if admin user exists
        $adminUser = DB::table('users')->where('id_user', 1)->first();
        if ($adminUser) {
            DB::table('activity_logs')->insert([
                'id_user' => 1,
                'action_type' => 'system_init',
                'target_type' => 'system',
                'target_id' => null,
                'description' => 'System initialized and database verified',
                'ip_address' => '127.0.0.1',
                'timestamp' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Rollback: remove the system init log
        DB::table('activity_logs')
            ->where('action_type', 'system_init')
            ->delete();

        // Rollback: remove auto-created progress records
        // This is optional - you might want to keep them
    }
};
