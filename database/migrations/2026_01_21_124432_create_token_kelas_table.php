<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('token_kelas', function (Blueprint $table) {
            $table->id('id_token');

            $table->foreignId('id_class')
                ->constrained('classes', 'id_class')
                ->cascadeOnDelete();

            $table->string('token_code', 50)->unique();

            $table->foreignId('created_by')
                ->constrained('users', 'id_user')
                ->cascadeOnDelete();

            $table->timestamp('expires_at')->nullable();
            $table->integer('max_uses')->default(0);   // 0 = unlimited
            $table->integer('times_used')->default(0);

            $table->timestamp('created_at')->useCurrent();

            // Index biar validasi token cepat
            $table->index('token_code');
            $table->index('id_class');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('token_kelas');
    }
};
