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
        Schema::table('sub_kriterias', function (Blueprint $table) {
            $table->foreignId('kriteria_id')->constrained('kriterias')->onDelete('cascade')->after('id');
            $table->string('nama_subkriteria')->after('kriteria_id');
            $table->text('deskripsi_subkriteria')->nullable()->after('nama_subkriteria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_kriterias', function (Blueprint $table) {
            $table->dropForeign(['kriteria_id']);
            $table->dropColumn(['kriteria_id', 'nama_subkriteria', 'deskripsi_subkriteria']);
        });
    }
};
