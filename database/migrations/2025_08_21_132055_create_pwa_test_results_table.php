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
        Schema::create('pwa_test_results', function (Blueprint $table) {
            $table->id();
            $table->string('test_id')->unique(); // Unique identifier for each test case
            $table->json('test_data')->nullable(); // Store test parameters and results
            $table->string('scenario')->nullable(); // stable_online, offline_mode, intermittent
            $table->integer('assessment_number')->nullable(); // 1-10
            $table->integer('repetition')->nullable(); // 1-3
            $table->boolean('success')->default(false); // Whether the test passed
            $table->decimal('processing_time', 8, 4)->nullable(); // Processing time in seconds
            $table->decimal('sync_delay', 8, 2)->nullable(); // Sync delay in seconds
            $table->string('local_hash')->nullable(); // SHA-256 hash of local data
            $table->string('server_hash')->nullable(); // SHA-256 hash of server data
            $table->boolean('data_integrity_check')->default(false); // Whether data integrity check passed
            $table->string('network_status')->nullable(); // online, offline, intermittent
            $table->integer('file_size')->nullable(); // File size in bytes
            $table->string('file_hash')->nullable(); // Hash of uploaded file
            $table->text('error_message')->nullable(); // Error message if test failed
            $table->json('performance_metrics')->nullable(); // Additional performance data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pwa_test_results');
    }
};
