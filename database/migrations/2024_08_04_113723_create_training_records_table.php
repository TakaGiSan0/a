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
            $table->string('doc_ref');
            $table->string('training_name', length: 200);
            $table->string('job_skill', length: 50)->nullable();
            $table->string('trainer_name', length: 50);
            $table->string('rev', length: 50)->nullable();
            $table->string('station', length: 50)->nullable();
            $table->string('skill_code', length: 50)->nullable();
            $table->enum('status', ['Completed', 'Pending'])->default('Pending');
            $table->enum('approval', ['Approved', 'Pending', 'Reject'])->default('Pending');
            $table->date('date_start');
            $table->date('date_end');
            $table->time('training_duration')->nullable();
            $table->string('comment')->nullable();
            $table->string('attachment')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('category_id')->references('id')->on
            ('categories');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
