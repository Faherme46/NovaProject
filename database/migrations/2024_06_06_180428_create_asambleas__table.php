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
        Schema::create('asambleas', function (Blueprint $table) {
            $table->id('id_asamblea');
            $table->string('name')->unique();
            $table->string('folder');
            $table->string('lugar');
            $table->string('ciudad');
            $table->date('fecha');
            $table->time('hora');
            $table->integer('controles');
            $table->string('referencia')->nullable();
            $table->string('tipo')->nullable();
            // $table->enum('estado', ['pendiente', 'en_progreso', 'finalizada','cancelada']);
            $table->boolean('registro');
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
        Schema::dropIfExists('asambleas');
    }
};
