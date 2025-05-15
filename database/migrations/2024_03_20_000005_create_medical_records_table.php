<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->text('diagnosis');
            $table->text('symptoms');
            $table->text('treatment');
            $table->text('prescription')->nullable();
            $table->text('notes')->nullable();
            $table->json('vital_signs')->nullable();
            $table->json('test_results')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('users');
            $table->foreign('doctor_id')->references('id')->on('users');
            $table->foreign('appointment_id')->references('id')->on('appointments');
        });
    }

    public function down()
    {
        Schema::dropIfExists('medical_records');
    }
};
