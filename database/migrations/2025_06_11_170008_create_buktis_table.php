<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buktis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temuan_id')->constrained('temuans')->onDelete('cascade');
            $table->foreignId('pengguna_unggah_id')->constrained('users')->comment('ID pengguna yang mengunggah bukti');
            $table->string('nama_dokumen');
            $table->string('file_path');
            $table->string('status')->default('menunggu verifikasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buktis');
    }
};
