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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('department', ['1', '2', '3'])->nullable();
            // $table->string('specimen_type');
            $table->foreignId('specimen_type')->constrained('specimen_types')->onDelete('');
            $table->decimal('cost', 8, 2)->nullable();
            $table->text('calculation_explanation')->nullable();
            $table->text('test_notes')->nullable();
            $table->text('reference_range')->nullable();
            $table->text('basic_low_value_ref_range')->nullable();
            $table->text('basic_high_value_ref_range')->nullable();
            $table->text('basic_unit_value_ref_range')->nullable();
            $table->text('male_low_value_ref_range')->nullable();
            $table->text('male_high_value_ref_range')->nullable();
            $table->text('male_unit_value_ref_range')->nullable();
            $table->text('female_low_value_ref_range')->nullable();
            $table->text('female_high_value_ref_range')->nullable();
            $table->text('female_unit_value_ref_range')->nullable();
            $table->text('nomanualvalues_ref_range')->nullable();
            $table->enum('urin_test_type', ['1', '2'])->nullable();
            // $table->unsignedBigInteger('test_profile_id')->nullable();
            // $table->foreign('test_profile_id')
            //       ->references('id')
            //       ->on('test_profiles')
            //       ->onDelete('cascade');
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_urine_type')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
