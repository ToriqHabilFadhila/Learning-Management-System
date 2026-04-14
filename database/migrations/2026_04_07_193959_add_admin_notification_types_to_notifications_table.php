<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * PostgreSQL tidak mengizinkan ALTER TYPE ADD VALUE di dalam transaksi.
     * Jadi kita harus mematikan fitur transaksi untuk file migrasi ini.
     */
    public $withinTransaction = false;

    public function up(): void
    {
        // 1. Tambah value baru ke ENUM notification_type yang sudah ada
        // IF NOT EXISTS sangat penting agar tidak error saat dijalankan ulang
        DB::statement("ALTER TYPE notification_type ADD VALUE IF NOT EXISTS 'user_registered'");
        DB::statement("ALTER TYPE notification_type ADD VALUE IF NOT EXISTS 'class_created'");
        DB::statement("ALTER TYPE notification_type ADD VALUE IF NOT EXISTS 'system_error'");
        DB::statement("ALTER TYPE notification_type ADD VALUE IF NOT EXISTS 'daily_stats'");
        DB::statement("ALTER TYPE notification_type ADD VALUE IF NOT EXISTS 'storage_warning'");

        // 2. Tambah kolom priority
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'priority')) {
                // Di Laravel, ->enum() di PostgreSQL akan membuat kolom tipe string
                // dengan CHECK constraint, kecuali kita buat TYPE manual.
                // Untuk 'priority', karena ini baru, Laravel akan menanganinya dengan aman.
                $table->string('priority')->default('medium')->after('type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'priority')) {
                $table->dropColumn('priority');
            }
        });

        // Note: Menghapus VALUE dari ENUM di Postgres memang sangat ribet,
        // biasanya dibiarkan saja karena tidak merusak struktur.
    }
};
