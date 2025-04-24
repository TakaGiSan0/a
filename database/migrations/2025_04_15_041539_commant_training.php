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
        Schema::create('comment_training', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('training_record_id');
            $table->enum('approval', ['Approved', 'Pending', 'Reject'])->default('Pending');
            $table->string('comment')->nullable();
            $table->foreign('training_record_id')->references('id')->on('training_records')->onDelete('cascade');
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
