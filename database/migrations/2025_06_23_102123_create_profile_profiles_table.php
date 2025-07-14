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
        Schema::create('profile_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_profile_id');
            $table->unsignedBigInteger('child_profile_id');
            $table->foreign('parent_profile_id')->references('id')->on('test_profiles')->onDelete('cascade');
            $table->foreign('child_profile_id')->references('id')->on('test_profiles')->onDelete('cascade');
            $table->unique(['parent_profile_id', 'child_profile_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_profiles');
    }
};
