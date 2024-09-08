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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type');
            $table->string('title');
            $table->string('optionA')->nullable();
            $table->string('optionB')->nullable();
            $table->string('optionC')->nullable();
            $table->string('optionD')->nullable();
            $table->string('optionE')->nullable();
            $table->string('optionF')->nullable();
            $table->boolean('prefab');
            $table->boolean('isValid')->default(false);
            $table->double('quorum')->nullable();
            $table->integer('predios')->nullable();
            $table->integer('seconds')->default(0);
            $table->string('resultTxt')->nullable();
            $table->boolean('coefGraph')->default(1);
            $table->timestamps();

            $table->foreign('type')->references('id')->on('question_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
