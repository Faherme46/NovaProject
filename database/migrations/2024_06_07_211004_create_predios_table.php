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
            $table->string('descriptor1');
            $table->string('numeral1');
            $table->string('descriptor2')->nullable();
            $table->string('numeral2')->nullable();
            $table->integer('votos')->default(1);
            $table->float('coeficiente');
            $table->boolean('vota');
            $table->unsignedBigInteger('control_id')->nullable();
            $table->boolean('quorum_start')->default(false);
            $table->boolean('quorum_end')->default(false);
            $table->unsignedBigInteger('cc_apoderado')->nullable();
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
