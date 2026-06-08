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
        // 🔹 ¡CORREGIDO AQUÍ! Ahora sí creará la tabla 'cultivos'
        Schema::create('cultivos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tipo')->nullable();
            $table->string('estado')->default('En desarrollo');
            $table->integer('area')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 🔹 ¡CORREGIDO AQUÍ TAMBIÉN!
        Schema::dropIfExists('cultivos');
    }
};