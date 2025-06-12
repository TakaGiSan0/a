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
        Schema::create('pesertas', function (Blueprint $table) {
            $table->id();
            $table->string('badge_no')->unique();
            $table->string('employee_name');
            $table->string('dept');
            $table->string('position');
            $table->date('join_date');
            $table->enum('status', ['Active', 'Non Active'])->default('Active');
            $table->enum('gender', ['Male', 'Female']);
            $table->string('category_level');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('user_id_login')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');;
            $table->foreign('user_id_login')->references('id')->on('users')->onDelete('set null');;
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesertas');
    }
};
