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
        Schema::create('sensitivity_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('sensitivity_profiles')->onDelete('cascade');
            $table->string('antibiotic');
            // $table->string('measure');
            // $table->string('designation');
            // $table->boolean('sensitivity')->default(false);
            // $table->boolean('resistant')->default(false);
            // $table->boolean('intermediate')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensitivity_items');
    }
};
