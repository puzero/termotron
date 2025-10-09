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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('last_name')->nullable(false);
            $table->string('first_name')->nullable(false);
            $table->string('middle_name')->nullable(false);
            $table->string('email');
            $table->string('phone');
            $table->date('birth_date');
            $table->string('status')->default('active');
            $table->string('resume_text');
            $table->date('application_date');
            $table->date('application_closing_date')->nullable();
            $table->date('interview_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
