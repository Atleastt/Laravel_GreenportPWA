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
        // Kolom 'peran' tidak diperlukan karena menggunakan Spatie roles.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak ada aksi karena kolom 'peran' tidak pernah dibuat.
    }
};
