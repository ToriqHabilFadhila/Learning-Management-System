<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id('id_material');

            $table->foreignId('id_class')
                ->constrained('classes', 'id_class')
                ->cascadeOnDelete();

            $table->string('judul', 200);
            $table->text('konten')->nullable();

            $table->string('file_path', 500)->nullable();
            $table->string('file_type', 50)->nullable();

            $table->foreignId('uploaded_by')
                ->constrained('users', 'id_user')
                ->cascadeOnDelete();

            $table->timestamps();

            // Index biar loading materi nggak nyendat
            $table->index('id_class');
            $table->index('uploaded_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
