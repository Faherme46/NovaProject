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
        Schema::create('propiedads', function (Blueprint $table) {
            $table->id();
            $table->string('cc_propietario');
            $table->string('descriptor1');
            $table->string('numeral1');
            $table->string('descriptor2');
            $table->string('numeral2');
            $table->float('coeficiente');
            $table->timestamps();

            $table->foreign('cc_propietario')->references('cedula')->on('personas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propiedads');
    }

};

$table->after('2024_06_07_141154_create_personas_table');