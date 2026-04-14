<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id('id_log');

            $table->foreignId('id_user')
                ->constrained('users', 'id_user')
                ->cascadeOnDelete();

            $table->string('action_type', 50);   // contoh: login, submit_assignment, join_class
            $table->string('target_type', 50)->nullable(); // class, assignment, material, dll
            $table->integer('target_id')->nullable();

            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable(); // IPv4 + IPv6 aman

            $table->timestamp('timestamp')->useCurrent();

            // Index biar audit & tracking cepat
            $table->index('id_user');
            $table->index('action_type');
            $table->index('timestamp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
