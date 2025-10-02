<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_audit_id')->constrained('checklist_audits')->onDelete('cascade');
            $table->foreignId('indikator_id')->constrained('indikators');
            $table->text('jawaban_teks')->nullable();
            $table->integer('jawaban_skala')->nullable();
            $table->boolean('jawaban_ya_tidak')->nullable();
            $table->decimal('skor_final', 5, 2)->nullable();
            $table->text('komentar_auditor')->nullable();
            // removed status_verifikasi per new structure
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_checklists');
    }
};
