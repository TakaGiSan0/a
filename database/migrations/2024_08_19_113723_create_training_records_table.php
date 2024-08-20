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
        Schema::create('training_records', function (Blueprint $table) {
            $table->id();
            $table->string('doc_ref')->unique();
            $table->string('badge_no');
            $table->string('employe_name');
            $table->string('dept');
            $table->string('position');
            $table->string('license')->nullable();
            $table->string('training_name');
            $table->string('job_skill');
            $table->string('trainer_name');
            $table->string('rev');
            $table->string('station');
            $table->string('skill_code');
            $table->date('training_date');
            $table->unsignedBigInteger('theory_result_id');
            $table->unsignedBigInteger('practical_result_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('final_judgement_id');
            $table->foreign('theory_result_id')->references('id')->on('theory_results');
            $table->foreign('practical_result_id')->references('id')->on('practical_results');
            $table->foreign('level_id')->references('id')->on('levels');
            $table->foreign('final_judgement_id')->references('id')->on('final_judgements');
            $table->foreign('category_id')->references('id')->on('categories');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_records');
    }
};
