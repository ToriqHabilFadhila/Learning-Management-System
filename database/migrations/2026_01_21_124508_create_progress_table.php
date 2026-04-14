<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ENUM status progress (PostgreSQL)
        DB::statement("CREATE TYPE progress_status AS ENUM ('in_progress', 'completed')");

        Schema::create('progress', function (Blueprint $table) {
            $table->id('id_progress');

            $table->foreignId('id_user')
                ->constrained('users', 'id_user')
                ->cascadeOnDelete();

            $table->foreignId('id_class')
                ->constrained('classes', 'id_class')
                ->cascadeOnDelete();

            $table->integer('persentase')
                ->default(0)
                ->check('persentase >= 0 AND persentase <= 100');

            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');

            $table->timestamp('last_activity')->nullable();
            $table->timestamps();

            // Satu progress per user per kelas
            $table->unique(['id_user', 'id_class']);

            // Index biar laporan & dashboard cepat
            $table->index('id_user');
            $table->index('id_class');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress');
        DB::statement('DROP TYPE IF EXISTS progress_status');
    }
};
