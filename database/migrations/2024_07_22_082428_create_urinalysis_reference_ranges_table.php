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
        Schema::create('urinalysis_reference_ranges', function (Blueprint $table) {
            $table->id();
            $table->string('analyte'); // Name of the analyte (e.g., s_gravity, ph, etc.)
            $table->text('urireference_range')->nullable();
            $table->text('low')->nullable();
            $table->text('high')->nullable();
            $table->text('male_low')->nullable();
            $table->text('male_high')->nullable();
            $table->text('female_low')->nullable();
            $table->text('female_high')->nullable();
            $table->text('nomanualvalues_ref_range')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urinalysis_reference_ranges');
    }
};
