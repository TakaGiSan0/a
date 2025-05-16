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
            $table->unsignedBigInteger('training_record_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('score');
            $table->text('comment')->nullable();
            $table->foreign('training_id')->constrained('training')->onDelete('cascade');
            $table->foreign('peserta_id')->constrained('peserta')->onDelete('cascade');
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
