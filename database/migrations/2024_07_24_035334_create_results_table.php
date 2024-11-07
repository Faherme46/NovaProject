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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->double('optionA')->nullable();
            $table->double('optionB')->nullable();
            $table->double('optionC')->nullable();
            $table->double('optionD')->nullable();
            $table->double('optionE')->nullable();
            $table->double('optionF')->nullable();
            $table->double('total')->nullable();
            $table->double('abstainted');
            $table->double('absent');
            $table->double('nule');
            $table->boolean('isCoef');
            $table->string('chartPath')->nullable();
            $table->timestamps();

            $table->foreign('question_id')->references('id')->on('questions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
