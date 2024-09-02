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
            $table->boolean('license')->default(0);
            $table->string('training_name',length:50);
            $table->string('job_skill', length:50);
            $table->string('trainer_name',length:50);
            $table->string('rev', length:50);
            $table->string('station', length:50);
            $table->string('skill_code',length:50);
            $table->date('training_date');
            $table->unsignedBigInteger('peserta_id');
            $table->enum('theory_result', ['pass', 'fail']);
            $table->enum('practical_result', ['pass', 'fail']);
            $table->unsignedBigInteger('category_id');
            $table->enum('level', ['level 1', 'level 2','level 3', 'level 4']);
            $table->enum('final_judgement', ['Competence', 'Attend']);
            $table->foreign('peserta_id')->references('id')->on('pesertas');
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
