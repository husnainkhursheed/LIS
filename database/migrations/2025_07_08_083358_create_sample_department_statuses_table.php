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
        Schema::create('sample_department_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sample_id')->nullable();
            $table->foreign('sample_id')
                  ->references('id')
                  ->on('samples')
                  ->onDelete('cascade');
            $table->enum('department', ['1', '2', '3'])->nullable();
            $table->text('note')->nullable();
            $table->boolean('is_signed')->default(false);
            $table->foreignId('signed_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('signed_at')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->foreignId('completed_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_department_statuses');
    }
};
