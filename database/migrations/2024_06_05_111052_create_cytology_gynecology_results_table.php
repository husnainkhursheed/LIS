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
        Schema::create('cytology_gynecology_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_report_id')->constrained('test_reports')->onDelete('cascade');
            $table->text('history')->nullable();
            $table->date('last_period')->nullable();
            $table->string('contraceptive')->nullable();
            $table->date('previous_pap')->nullable();
            $table->text('result')->nullable();
            $table->text('cervix_examination')->nullable();
            $table->text('specimen_adequacy')->nullable();
            $table->text('diagnostic_interpretation')->nullable();
            $table->text('recommend')->nullable();
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
        Schema::dropIfExists('cytology_gynecology_results');
    }
};
