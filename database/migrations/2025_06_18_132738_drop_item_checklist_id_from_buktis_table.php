<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buktis', function (Blueprint $table) {
            // Drop foreign key and remove the unused column
            if (Schema::hasColumn('buktis', 'item_checklist_id')) {
                $table->dropForeign(['item_checklist_id']);
                $table->dropColumn('item_checklist_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('buktis', function (Blueprint $table) {
            // Restore the column if rolling back
            $table->foreignId('item_checklist_id')
                  ->constrained('item_checklists')
                  ->onDelete('cascade');
        });
    }
};
