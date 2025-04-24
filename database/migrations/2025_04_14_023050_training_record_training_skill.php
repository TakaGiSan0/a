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
        Schema::create('training_record_training_skill', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('training_skill_id');
            $table->foreignId('training_record_id')->constrained()->onDelete('cascade');
            $table->foreign('training_skill_id')->references('id')->on('training_skill');
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
