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
        Schema::table('laporans', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
            $table->foreignId('audit_id')->nullable()->constrained('audits')->onDelete('cascade');
            $table->text('executive_summary')->nullable();
            $table->text('findings_recommendations')->nullable();
            $table->decimal('compliance_score', 5, 2)->nullable();
            $table->date('period_start')->nullable()->change();
            $table->date('period_end')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->renameColumn('title', 'name');
            $table->dropForeign(['audit_id']);
            $table->dropColumn(['audit_id', 'executive_summary', 'findings_recommendations', 'compliance_score']);
            $table->date('period_start')->nullable(false)->change();
            $table->date('period_end')->nullable(false)->change();
        });
    }
};
