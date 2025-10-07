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
        Schema::create('emploees', function (Blueprint $table) {
            $table->id();
            $table->string('last_name')->nullable(false);
            $table->string('first_name')->nullable(false);
            $table->string('middle_name')->nullable(false);
            $table->unsignedBigInteger('position_id')->nullable(false);
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
            $table->date('hire_date');
            $table->string('email');
            $table->string('phone');
            $table->date('birth_date');
            $table->decimal('salary',10,2);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emploees');
    }
};
