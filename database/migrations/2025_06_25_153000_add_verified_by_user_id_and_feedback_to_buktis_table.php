<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buktis', function (Blueprint $table) {
            $table->foreignId('verified_by_user_id')
                  ->nullable()
                  ->constrained('users')
                  ->after('status')
                  ->comment('ID auditor yang memverifikasi');
            $table->text('auditor_feedback')
                  ->nullable()
                  ->after('verified_by_user_id')
                  ->comment('Catatan/rekomendasi auditor');
        });
    }

    public function down(): void
    {
        Schema::table('buktis', function (Blueprint $table) {
            $table->dropConstrainedForeignId('verified_by_user_id');
            $table->dropColumn('auditor_feedback');
        });
    }
}; 