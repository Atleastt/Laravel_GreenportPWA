<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indikators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subkriteria_id')->constrained('subkriterias')->onDelete('cascade');
            $table->text('teks_indikator');
            $table->decimal('bobot', 5, 2)->default(1.0);
            $table->integer('poin')->default(0);
            $table->enum('tipe_jawaban', ['skala', 'teks', 'ya_tidak', 'file_only']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indikators');
    }
};
