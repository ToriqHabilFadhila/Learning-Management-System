<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ENUM tipe tugas (PostgreSQL)
        DB::statement("CREATE TYPE assignment_type AS ENUM ('pilihan_ganda', 'essay', 'praktik')");

        Schema::create('assignments', function (Blueprint $table) {
            $table->id('id_assignment');

            $table->foreignId('id_class')
                ->constrained('classes', 'id_class')
                ->cascadeOnDelete();

            $table->string('judul', 200);
            $table->text('deskripsi')->nullable();

            $table->enum('tipe', ['pilihan_ganda', 'essay', 'praktik']);

            $table->timestamp('deadline');
            $table->integer('max_score')->default(100);

            $table->foreignId('created_by')
                ->constrained('users', 'id_user')
                ->cascadeOnDelete();

            $table->timestamps();

            // Index biar laporan & dashboard sat-set
            $table->index('id_class');
            $table->index('created_by');
            $table->index('deadline');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
        DB::statement('DROP TYPE IF EXISTS assignment_type');
    }
};
