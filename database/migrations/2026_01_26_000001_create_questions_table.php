<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id('id_question');
            
            $table->foreignId('id_assignment')
                ->constrained('assignments', 'id_assignment')
                ->cascadeOnDelete();
            
            $table->text('soal');
            $table->integer('poin')->default(10);
            $table->integer('urutan')->default(1);
            
            $table->timestamps();
            
            $table->index('id_assignment');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
