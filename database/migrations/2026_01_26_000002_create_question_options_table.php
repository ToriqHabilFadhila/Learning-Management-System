<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_options', function (Blueprint $table) {
            $table->id('id_option');
            
            $table->foreignId('id_question')
                ->constrained('questions', 'id_question')
                ->cascadeOnDelete();
            
            $table->text('pilihan');
            $table->boolean('is_correct')->default(false);
            
            $table->timestamps();
            
            $table->index('id_question');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_options');
    }
};
