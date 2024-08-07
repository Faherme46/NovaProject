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
        Schema::create('control_predios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('control_id');
            $table->unsignedBigInteger('predio_id');
            $table->timestamps();
            $table->foreign('control_id')->references('id')->on('controls')->onDelete('cascade');
            $table->foreign('predio_id')->references('id')->on('predios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_predios');
    }
};
