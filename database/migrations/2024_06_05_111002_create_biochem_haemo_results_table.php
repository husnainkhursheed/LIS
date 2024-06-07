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
        Schema::create('biochem_haemo_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_report_id')->constrained('test_reports')->onDelete('cascade');
            $table->text('reference')->nullable();
            $table->text('note')->nullable();
            $table->text('description')->nullable();
            $table->text('test_results')->nullable();
            $table->text('flag')->nullable();
            $table->text('reference_range')->nullable();
            $table->text('test_notes')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biochem_haemo_results');
    }
};
