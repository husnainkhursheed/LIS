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
        Schema::create('sensitivity_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit')->default('MIC (ug/mL)'); // Default unit can be changed as needed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensitivity_profiles');
    }
};
