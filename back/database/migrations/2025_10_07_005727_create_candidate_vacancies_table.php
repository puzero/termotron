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
        Schema::create('candidate_vacancies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id')->nullable(false);
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
            $table->unsignedBigInteger('vacancy_id')->nullable(false);
            $table->foreign('vacancy_id')->references('id')->on('vacancies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_vacancies');
    }
};
