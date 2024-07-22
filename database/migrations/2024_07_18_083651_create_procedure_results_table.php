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
        Schema::create('procedure_results', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('urinalysis_microbiology_result_id')->constrained('urinalysis_microbiology_results')->onDelete('cascade');
            $table->unsignedBigInteger('urinalysis_microbiology_result_id')->nullable();
            $table->foreign('urinalysis_microbiology_result_id')
                  ->references('id')
                  ->on('urinalysis_microbiology_results')
                  ->onDelete('cascade');
            $table->string('procedure')->nullable();
            $table->text('specimen_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedure_results');
    }
};
