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
            $table->foreignId('test_report_id')->constrained('test_reports');
            $table->float('s_gravity')->nullable();
            $table->float('ph')->nullable();
            $table->string('bilirubin')->nullable();
            $table->string('blood')->nullable();
            $table->string('leucocytes')->nullable();
            $table->string('glucose')->nullable();
            $table->string('nitrite')->nullable();
            $table->string('ketones')->nullable();
            $table->string('urobilinogen')->nullable();
            $table->string('proteins')->nullable();
            $table->string('colour')->nullable();
            $table->string('appearance')->nullable();
            $table->text('epith_cells')->nullable();
            $table->string('bacteria')->nullable();
            $table->text('white_cells')->nullable();
            $table->text('yeast')->nullable();
            $table->text('red_cells')->nullable();
            $table->text('trichomonas')->nullable();
            $table->text('casts')->nullable();
            $table->text('crystals')->nullable();
            $table->text('specimen')->nullable();
            $table->string('procedure')->nullable();
            $table->text('sensitivity')->nullable();
            $table->text('review')->nullable();
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
