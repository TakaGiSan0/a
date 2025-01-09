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
        Schema::create('hasil_peserta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peserta_id');
            $table->unsignedBigInteger('training_record_id');
            $table->enum('theory_result', ['Pass', 'Fail', 'NA'])->nullable();
            $table->enum('practical_result', ['Pass', 'Fail', 'NA'])->nullable();
            $table->enum('level', ['Level 1', 'Level 2','Level 3', 'Level 4' , 'NA'])->nullable();
            $table->enum('final_judgement', ['Competence', 'Attend', 'NA'])->nullable();
            $table->boolean('license')->default(0);
            $table->foreign('peserta_id')->references('id')->on('pesertas')->onDelete('cascade');
            $table->foreign('training_record_id')->references('id')->on('training_records')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil');
    }
};
