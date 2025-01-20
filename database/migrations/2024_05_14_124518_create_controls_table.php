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
        Schema::create('controls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('state')->default(4);
            $table->unsignedBigInteger('cc_asistente')->nullable();
            $table->decimal('sum_coef_can', 8, 6);
            $table->integer('predios_vote');
            $table->integer('votes');
            $table->decimal('sum_coef', 8, 6);
            $table->string('h_entrega')->nullable();//se le entrega al cliente
            $table->string('h_recibe')->nullable();//recibe el operario
            $table->string('t_publico')->default(0);
            $table->timestamps();
            $table->foreign('state')->references('id')->on('states');
            $table->foreign('cc_asistente')->references('id')->on('personas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('controls');
    }
};
