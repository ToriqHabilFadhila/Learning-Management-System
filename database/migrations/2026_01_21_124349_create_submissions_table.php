<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ENUM status submission (PostgreSQL)
        DB::statement("CREATE TYPE submission_status AS ENUM ('submitted', 'graded', 'late')");

        Schema::create('submissions', function (Blueprint $table) {
            $table->id('id_submission');

            $table->foreignId('id_assignment')
                ->constrained('assignments', 'id_assignment')
                ->cascadeOnDelete();

            $table->foreignId('id_user')
                ->constrained('users', 'id_user')
                ->cascadeOnDelete();

            $table->text('jawaban')->nullable();
            $table->string('file_path', 500)->nullable();

            $table->timestamp('submitted_at')->useCurrent();

            $table->decimal('score', 5, 2)->nullable();
            $table->enum('status', ['submitted', 'graded', 'late'])->default('submitted');

            $table->foreignId('graded_by')
                ->nullable()
                ->constrained('users', 'id_user')
                ->nullOnDelete();

            $table->timestamp('graded_at')->nullable();

            // Satu siswa satu submission per tugas
            $table->unique(['id_assignment', 'id_user']);

            // Index biar koreksi & rekap ngebut
            $table->index('id_assignment');
            $table->index('id_user');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
        DB::statement('DROP TYPE IF EXISTS submission_status');
    }
};
