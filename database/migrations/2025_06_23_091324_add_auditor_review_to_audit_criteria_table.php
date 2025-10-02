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
        Schema::table('audit_criteria', function (Blueprint $table) {
            $table->text('auditor_review_notes')->nullable()->after('auditee_attachment_path');
            $table->string('auditor_review_status')->nullable()->default('Pending')->after('auditor_review_notes'); // e.g., Pending, Approved, RevisionNeeded
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_criteria', function (Blueprint $table) {
            $table->dropColumn(['auditor_review_notes', 'auditor_review_status']);
        });
    }
};
