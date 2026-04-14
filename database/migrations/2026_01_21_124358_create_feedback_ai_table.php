<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback_ai', function (Blueprint $table) {
            $table->id('id_feedback');

            $table->foreignId('id_submission')
                ->unique()
                ->constrained('submissions', 'id_submission')
                ->cascadeOnDelete();

            $table->text('feedback_text');
            $table->text('saran')->nullable();
            $table->text('rekomendasi_materi')->nullable();
            $table->text('question')->nullable();
            $table->text('answer')->nullable();

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_ai');
    }
};
