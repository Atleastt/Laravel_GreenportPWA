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
        Schema::create('audit_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained()->onDelete('cascade');
            $table->foreignId('criterion_id')->constrained('indikators')->onDelete('cascade'); // Assuming 'criterion_id' refers to 'indikators' table
            $table->integer('score')->nullable();
            $table->text('auditor_notes')->nullable();
            $table->text('auditee_notes')->nullable();
            $table->string('auditee_attachment_path')->nullable();
            $table->string('status')->default('Open'); // e.g., Open, InProgress, Closed
            $table->timestamps();

            $table->unique(['audit_id', 'criterion_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_criteria');
    }
};
