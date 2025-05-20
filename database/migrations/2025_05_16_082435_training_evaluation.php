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
        Schema::create('training_request', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id_login');
            $table->unsignedBigInteger('peserta_id');
            $table->text('description')->nullable();
            $table->foreign('user_id_login')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('peserta_id')->references('id')->on('pesertas')->onDelete('cascade');
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
