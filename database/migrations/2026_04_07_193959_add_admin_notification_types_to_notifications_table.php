<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add new notification types for admin - each statement must be executed separately
        DB::statement("ALTER TYPE notification_type ADD VALUE IF NOT EXISTS 'user_registered'");
        DB::statement("ALTER TYPE notification_type ADD VALUE IF NOT EXISTS 'class_created'");
        DB::statement("ALTER TYPE notification_type ADD VALUE IF NOT EXISTS 'system_error'");
        DB::statement("ALTER TYPE notification_type ADD VALUE IF NOT EXISTS 'daily_stats'");
        DB::statement("ALTER TYPE notification_type ADD VALUE IF NOT EXISTS 'storage_warning'");

        // Add priority column
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'priority')) {
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->after('type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'priority')) {
                $table->dropColumn('priority');
            }
        });
        
        // Note: Cannot remove enum values in PostgreSQL easily
        // Would need to recreate the type
    }
};
