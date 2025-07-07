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
        Schema::create('urinalysis_microbiology_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_report_id')->constrained('test_reports')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->text('test_results')->nullable();
            $table->text('flag')->nullable();
            $table->text('reference_range')->nullable();
            $table->text('test_notes')->nullable();
            $table->text('note')->nullable();
            $table->text('sensitivity_profiles')->nullable();
            $table->text('sensitivity')->nullable();
            $table->text('review')->nullable();
            $table->boolean('is_signed')->default(false);
            $table->foreignId('signed_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('signed_at')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->foreignId('completed_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urinalysis_microbiology_results');
    }
};
