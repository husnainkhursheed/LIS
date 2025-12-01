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
         Schema::create('profile_formulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('test_profiles')->onDelete('cascade');
            $table->foreignId('calculated_test_id')->constrained('tests')->onDelete('cascade');
            $table->text('formula'); // Store as JSON: {"operator": "divide", "operand1": "test_id", "operand2": 5}
            $table->integer('calculation_order')->default(0); // For dependencies
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_formulas');
    }
};
