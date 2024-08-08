<?php
//TODO validar que no puedan crearse predios dos veces
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
        Schema::create('predios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cc_apoderado')->nullable();
            $table->unsignedBigInteger('control_id')->nullable();
            $table->string('descriptor1');
            $table->string('numeral1');
            $table->string('descriptor2');
            $table->string('numeral2');
            $table->float('coeficiente');
            $table->boolean('vota');
            $table->timestamps();
            $table->foreign('cc_apoderado')->references('id')->on('personas');
            $table->foreign('control_id')->references('id')->on('controls');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predios');
    }

};
