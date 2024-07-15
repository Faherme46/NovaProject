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
            $table->unsignedBigInteger('state')->default(1);
            $table->unsignedBigInteger('cc_asistente')->nullable();
            $table->decimal('sum_coef', 8, 6);
            $table->timestamps();
            $table->foreign('cc_asistente')->references('id')->on('personas')->onDelete('cascade');
            $table->foreign('state')->references('id')->on('states')->onDelete('cascade');
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
