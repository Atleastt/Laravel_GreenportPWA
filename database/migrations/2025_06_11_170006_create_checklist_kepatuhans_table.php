<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_kepatuhans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('audits')->onDelete('cascade');
            $table->foreignId('checklist_template_id')->constrained('checklist_templates');
            $table->enum('status', ['Belum Diisi', 'Terkirim']);
            $table->timestamp('tanggal_submit_auditee')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_kepatuhans');
    }
};
