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
        Schema::create('tindak_lanjuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekomendasi_id')->constrained('rekomendasis')->onDelete('cascade');
            $table->foreignId('auditee_id')->constrained('users')->onDelete('cascade');
            $table->text('rencana_tindakan');
            $table->date('target_penyelesaian');
            $table->string('penanggung_jawab');
            $table->text('sumber_daya_dibutuhkan')->nullable();
            $table->enum('status_progres', ['Direncanakan', 'Sedang Berjalan', 'Hampir Selesai', 'Selesai', 'Terhambat'])->default('Direncanakan');
            $table->integer('persentase_penyelesaian')->default(0);
            $table->text('catatan_progres')->nullable();
            $table->string('bukti_penyelesaian')->nullable();
            $table->json('milestones')->nullable();
            $table->timestamp('tanggal_update_terakhir')->nullable();
            $table->timestamps();

            $table->index(['rekomendasi_id', 'status_progres']);
            $table->index(['auditee_id', 'status_progres']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tindak_lanjuts');
    }
};
