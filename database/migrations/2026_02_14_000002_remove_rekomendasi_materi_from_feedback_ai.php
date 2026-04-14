<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feedback_ai', function (Blueprint $table) {
            $table->dropColumn('rekomendasi_materi');
        });
    }

    public function down(): void
    {
        Schema::table('feedback_ai', function (Blueprint $table) {
            $table->text('rekomendasi_materi')->nullable();
        });
    }
};
