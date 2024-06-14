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
        Schema::create('asignacion_predios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asignacion_id');
            $table->unsignedBigInteger('predio_id');
            $table->timestamps();
            $table->foreign('asignacion_id')->references('id')->on('asignacions')->onDelete('cascade');
            $table->foreign('predio_id')->references('id')->on('predios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion_predios');
    }
};
