<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            // Tambah kolom baru
            $table->string('title')->nullable()->after('id');
            $table->date('scheduled_start_date')->nullable()->after('auditee_id');
            $table->date('scheduled_end_date')->nullable()->after('scheduled_start_date');
        });

        // Pindahkan data dari kolom lama
        DB::table('audits')->update([
            'title' => DB::raw('judul'),
            'scheduled_start_date' => DB::raw('tanggal_jadwal'),
        ]);

        Schema::table('audits', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn(['judul', 'tanggal_jadwal']);
        });

        // Ubah enum status ke bahasa Inggris
        Schema::table('audits', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('audits', function (Blueprint $table) {
            $table->enum('status', ['Scheduled', 'InProgress', 'Completed', 'Revising'])
                  ->default('Scheduled')
                  ->after('scheduled_end_date');
        });
    }

    public function down(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            // Kembalikan kolom lama
            $table->string('judul')->after('id');
            $table->date('tanggal_jadwal')->after('auditee_id');
        });

        // Pindahkan data kembali
        DB::table('audits')->update([
            'judul' => DB::raw('title'),
            'tanggal_jadwal' => DB::raw('scheduled_start_date'),
        ]);

        Schema::table('audits', function (Blueprint $table) {
            // Hapus kolom baru
            $table->dropColumn(['title', 'scheduled_start_date', 'scheduled_end_date']);
            $table->dropColumn('status');
        });

        Schema::table('audits', function (Blueprint $table) {
            // Kembalikan enum status lama
            $table->enum('status', ['Dijadwalkan', 'Berlangsung', 'Selesai', 'Ditinjau'])
                  ->default('Dijadwalkan')
                  ->after('tanggal_jadwal');
        });
    }
}; 