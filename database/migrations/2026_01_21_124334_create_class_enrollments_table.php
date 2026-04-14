<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ENUM status enrollment (PostgreSQL)
        DB::statement("CREATE TYPE enrollment_status AS ENUM ('active', 'completed', 'dropped')");

        Schema::create('class_enrollments', function (Blueprint $table) {
            $table->id('id_enrollment');

            $table->foreignId('id_class')
                ->constrained('classes', 'id_class')
                ->cascadeOnDelete();

            $table->foreignId('id_user')
                ->constrained('users', 'id_user')
                ->cascadeOnDelete();

            $table->timestamp('enrollment_date')->useCurrent();
            $table->enum('status', ['active', 'completed', 'dropped'])->default('active');
            $table->string('token_used', 50)->nullable();
            $table->timestamp('created_at')->useCurrent();

            // Satu user cuma boleh sekali di satu kelas
            $table->unique(['id_class', 'id_user']);

            // Index buat query cepat
            $table->index('id_class');
            $table->index('id_user');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_enrollments');
        DB::statement('DROP TYPE IF EXISTS enrollment_status');
    }
};
