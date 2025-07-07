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
        Schema::create('sensitivity_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sample_id')->nullable();
            $table->foreign('sample_id')
                  ->references('id')
                  ->on('samples')
                  ->onDelete('cascade');
            $table->text('sensitivity_profiles')->nullable();
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
        Schema::dropIfExists('sensitivity_results');
    }
};
