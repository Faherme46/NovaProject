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
        Schema::create('torres', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('delegados')->default(0);
            $table->integer('votos')->default(0);
            $table->float('coeficiente')->default(0);
            $table->integer('votosBlanco')->default(0);
            $table->float('coeficienteBlanco')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('torres');
    }
};
