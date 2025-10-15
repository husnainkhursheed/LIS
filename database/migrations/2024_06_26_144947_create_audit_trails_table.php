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
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id();
            $table->integer('test_report_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('changed_at');
            $table->string('field_name')->nullable();
            $table->text('from_value')->nullable();
            $table->text('to_value')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
    }
};
