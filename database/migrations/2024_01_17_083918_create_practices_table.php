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
        Schema::create('practices', function (Blueprint $table) {
            $table->id();
            $table->string('v_name')->nullable();
            $table->text('address')->nullable();
            $table->string('town')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->unsignedBigInteger('inserted_by')->default(0);
            $table->unsignedBigInteger('updated_by')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setup_practices');
    }
};
