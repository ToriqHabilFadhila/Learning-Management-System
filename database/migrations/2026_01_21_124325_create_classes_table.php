<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // PERBAIKAN: Hapus type lama jika ada sisa dari migrasi sebelumnya
        DB::statement('DROP TYPE IF EXISTS class_status');

        // ENUM status kelas (PostgreSQL)
        DB::statement("CREATE TYPE class_status AS ENUM ('active', 'archived')");

        Schema::create('classes', function (Blueprint $table) {
            $table->id('id_class');
            $table->string('nama_kelas', 100);
            $table->text('deskripsi')->nullable();
            $table->foreignId('created_by')
                ->constrained('users', 'id_user')
                ->cascadeOnDelete();
            $table->integer('max_students')->default(50);
            $table->enum('status', ['active', 'archived'])->default('active');
            $table->timestamps();

            // Index
            $table->index('created_by');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
        DB::statement('DROP TYPE IF EXISTS class_status');
    }
};
