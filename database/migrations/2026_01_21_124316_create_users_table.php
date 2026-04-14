<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // PERBAIKAN: Hapus tipe lama jika ada sebelum membuat yang baru
        DB::statement('DROP TYPE IF EXISTS user_role');

        DB::statement("CREATE TYPE user_role AS ENUM ('siswa', 'guru', 'admin')");

        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('password');

            // Di Postgres, setelah TYPE dibuat, kita bisa langsung pakai
            $table->enum('role', ['siswa', 'guru', 'admin'])->default('siswa');

            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamps();

            // Index
            $table->index('email');
            $table->index('role');
            $table->index('is_active');
            $table->index('email_verified_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        // Pastikan ini juga ada di down()
        DB::statement('DROP TYPE IF EXISTS user_role');
    }
};
