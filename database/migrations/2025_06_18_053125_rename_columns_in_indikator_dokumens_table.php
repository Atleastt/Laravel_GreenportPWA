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
        Schema::table('indikator_dokumens', function (Blueprint $table) {
            $table->renameColumn('name', 'nama_indikator');
            $table->renameColumn('description', 'deskripsi');
            $table->renameColumn('category', 'kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('indikator_dokumens', function (Blueprint $table) {
            $table->renameColumn('nama_indikator', 'name');
            $table->renameColumn('deskripsi', 'description');
            $table->renameColumn('kategori', 'category');
        });
    }
};
