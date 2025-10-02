<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            // $table->text('deskripsi_audit'); // removed as per new spec
            $table->foreignId('auditor_id')->constrained('users');
            $table->foreignId('auditee_id')->constrained('users');
            $table->date('tanggal_jadwal');
            // $table->date('tanggal_selesai')->nullable(); // removed
            $table->enum('status', ['Dijadwalkan', 'Berlangsung', 'Selesai', 'Ditinjau']);
            // $table->decimal('skor_keseluruhan', 5, 2)->nullable(); // removed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
