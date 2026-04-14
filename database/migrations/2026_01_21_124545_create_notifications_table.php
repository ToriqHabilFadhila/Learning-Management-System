<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ENUM tipe notifikasi (PostgreSQL)
        DB::statement("
            CREATE TYPE notification_type AS ENUM (
                'feedback',
                'grade',
                'new_material',
                'new_assignment',
                'deadline'
            )
        ");

        Schema::create('notifications', function (Blueprint $table) {
            $table->id('id_notification');

            $table->foreignId('id_user')
                ->constrained('users', 'id_user')
                ->cascadeOnDelete();

            $table->enum('type', [
                'feedback',
                'grade',
                'new_material',
                'new_assignment',
                'deadline'
            ]);

            $table->string('title', 200);
            $table->text('message');

            $table->boolean('is_read')->default(false);
            $table->integer('related_id')->nullable();

            $table->timestamp('created_at')->useCurrent();

            // Index biar notif cepat kebaca
            $table->index('id_user');
            $table->index('is_read');
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        DB::statement('DROP TYPE IF EXISTS notification_type');
    }
};
