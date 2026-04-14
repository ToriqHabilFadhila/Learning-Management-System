<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if user_notifications table exists (after rename migration)
        if (Schema::hasTable('user_notifications')) {
            DB::statement('ALTER TABLE user_notifications DROP CONSTRAINT IF EXISTS notifications_type_check');
            DB::statement("ALTER TABLE user_notifications ADD CONSTRAINT notifications_type_check CHECK (type IN ('new_assignment', 'new_material', 'grade', 'feedback', 'class_created', 'deadline'))");
        } else {
            // If table still named notifications (before rename)
            DB::statement('ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_type_check');
            DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_type_check CHECK (type IN ('new_assignment', 'new_material', 'grade', 'feedback', 'class_created', 'deadline'))");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('user_notifications')) {
            DB::statement('ALTER TABLE user_notifications DROP CONSTRAINT IF EXISTS notifications_type_check');
            DB::statement("ALTER TABLE user_notifications ADD CONSTRAINT notifications_type_check CHECK (type IN ('new_assignment', 'new_material', 'grade', 'feedback'))");
        } else {
            DB::statement('ALTER TABLE notifications DROP CONSTRAINT IF EXISTS notifications_type_check');
            DB::statement("ALTER TABLE notifications ADD CONSTRAINT notifications_type_check CHECK (type IN ('new_assignment', 'new_material', 'grade', 'feedback'))");
        }
    }
};
