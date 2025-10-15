<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecimenTypesTable extends Migration
{
    public function up()
    {
        Schema::create('specimen_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('priority')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('specimen_types');
    }
}