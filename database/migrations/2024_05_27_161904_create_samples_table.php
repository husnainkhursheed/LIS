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
            $table->foreignId('patient_id')->constrained('patients');
            $table->foreignId('institution_id')->nullable()->constrained('institutions');
            $table->foreignId('doctor_id')->nullable()->constrained('doctors');
            $table->enum('bill_to', ['Patient', 'Doctor', 'Other']);
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
