<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('patient_files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('patient_id');
            $table->string('test_result');
            $table->string('X_ray_result');
            $table->enum('resident',['yes','no'])->default('no');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department__patients');
    }
};
