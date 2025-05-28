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
       Schema::create('training_evaluation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hasil_peserta_id');
            $table->text('question_1')->nullable();
            $table->text('question_2')->nullable();
            $table->text('question_3')->nullable();
            $table->text('question_4')->nullable();
            $table->text('question_5')->nullable();
            $table->enum('status', ['Completed', 'Pending', 'Waiting Approval'])->default('Waiting Approval');
            $table->foreign('hasil_peserta_id')->references('id')->on('hasil_peserta')->onDelete('cascade');
      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
