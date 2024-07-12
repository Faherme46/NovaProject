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
        Schema::create('asignacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('control_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('cc_asistente')->nullable();
            $table->string('estado');
            $table->decimal('sum_coef', 8, 6);
            $table->timestamps();
            $table->foreign('cc_asistente')->references('id')->on('personas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacions');
    }
};
