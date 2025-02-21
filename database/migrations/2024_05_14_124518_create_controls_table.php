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
            $table->unsignedBigInteger('terminal_id')->nullable();
            $table->unsignedBigInteger('cc_asistente')->nullable();
            $table->float('sum_coef_can')->default(0);
            $table->float('sum_coef')->default(0);
            $table->float('sum_coef_abs')->default(0);
            $table->integer('predios_total')->default(0);
            $table->integer('predios_vote')->default(0);
            $table->integer('predios_abs')->default(0);
            $table->string('h_entrega')->nullable();//se le entrega al cliente
            $table->string('h_recibe')->nullable();//recibe el operario
            $table->string('t_publico')->default(0);
            $table->string('vote')->nullable();
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
