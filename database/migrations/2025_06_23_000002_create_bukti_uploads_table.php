<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukti_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_checklist_id')->constrained('item_checklists')->onDelete('cascade');
            $table->foreignId('uploader_id')->constrained('users');
            $table->string('nama_file_original');
            $table->string('path_file_di_server')->nullable();
            $table->string('tipe_file');
            $table->unsignedBigInteger('ukuran_file');
            $table->enum('status_upload', ['Lokal', 'Tersinkronisasi', 'Gagal']);
            $table->string('checksum_sha256')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukti_uploads');
    }
};
