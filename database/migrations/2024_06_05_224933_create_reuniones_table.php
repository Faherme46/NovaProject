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
        Schema::create('reuniones', function (Blueprint $table) {
            $table->id('id_reunion');
            $table->string('nombre');
            $table->string('lugar');
            $table->date('fecha');
            $table->time('hora');
            $table->enum('estado', ['pendiente', 'en_progreso', 'finalizada','cancelada']);
            $table->boolean('registro');
            $table->string('nombreBd');
            $table->time('h_inicio')->nullable();
            $table->time('h_cierre')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reuniones');
    }
};
