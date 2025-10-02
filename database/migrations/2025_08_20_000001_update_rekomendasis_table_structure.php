<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rekomendasis', function (Blueprint $table) {
            // Add missing columns that are being sent from the form
            $table->foreignId('auditor_id')->nullable()->constrained('users')->after('audit_id');
            $table->enum('kategori', ['Mayor', 'Minor', 'Observasi'])->after('auditor_id');
            $table->text('deskripsi_temuan')->after('kategori');
            $table->text('rekomendasi_perbaikan')->after('deskripsi_temuan');
            $table->enum('prioritas', ['Tinggi', 'Sedang', 'Rendah'])->after('rekomendasi_perbaikan');
            $table->enum('status', ['Open', 'InProgress', 'Completed'])->default('Open')->after('prioritas');
            
            // Add additional columns for follow-up actions
            $table->text('catatan_tindak_lanjut')->nullable()->after('status');
            $table->timestamp('tanggal_tindak_lanjut')->nullable()->after('catatan_tindak_lanjut');
            $table->string('bukti_perbaikan_path')->nullable()->after('tanggal_tindak_lanjut');
            
            // Drop old columns that don't match
            $table->dropColumn(['teks_rekomendasi', 'status_rekomendasi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekomendasis', function (Blueprint $table) {
            // Restore original columns
            $table->text('teks_rekomendasi');
            $table->enum('status_rekomendasi', ['Terbuka', 'Selesai']);
            
            // Drop new columns
            $table->dropForeign(['auditor_id']);
            $table->dropColumn([
                'auditor_id',
                'kategori', 
                'deskripsi_temuan',
                'rekomendasi_perbaikan',
                'prioritas',
                'status',
                'catatan_tindak_lanjut',
                'tanggal_tindak_lanjut',
                'bukti_perbaikan_path'
            ]);
        });
    }
};
