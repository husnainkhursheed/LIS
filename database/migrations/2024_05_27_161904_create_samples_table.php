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
        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->string('test_number', 6)->unique();
            $table->string('access_number')->unique();
            $table->date('collected_date');
            $table->date('received_date');
            $table->time('received_time');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('institution_id')->nullable()->constrained('institutions')->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->onDelete('cascade');
            $table->enum('bill_to', ['Patient', 'Doctor', 'Other']);
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_signed')->default(false);
            $table->foreignId('signed_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->date('signed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->date('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('samples');
    }
};
