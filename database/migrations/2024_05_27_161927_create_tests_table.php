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
            $table->enum('department', ['1', '2', '3']);
            $table->string('specimen_type');
            $table->decimal('cost', 8, 2);
            $table->text('reference_range')->nullable();
            $table->text('basic_low_value_ref_range')->nullable();
            $table->text('basic_high_value_ref_range')->nullable();
            $table->text('male_low_value_ref_range')->nullable();
            $table->text('male_high_value_ref_range')->nullable();
            $table->text('female_low_value_ref_range')->nullable();
            $table->text('female_high_value_ref_range')->nullable();
            $table->tinyInteger('is_active')->default(1);
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
