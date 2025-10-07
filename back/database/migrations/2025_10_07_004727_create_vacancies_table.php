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
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('description');
            $table->string('requirements')->nullable(false);
            $table->string('responsibilities');
            $table->unsignedBigInteger('position_id')->nullable(false);
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
            $table->unsignedBigInteger('department_id')->nullable(false);
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->string('experience');
            $table->string('status')->default('draft');
            $table->integer('candidate_count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
