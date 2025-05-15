<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medical_record_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->date('prescription_date');
            $table->json('medications');
            $table->text('instructions')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'dispensed', 'completed']);
            $table->unsignedBigInteger('pharmacist_id')->nullable();
            $table->timestamp('dispensed_at')->nullable();
            $table->timestamps();

            $table->foreign('medical_record_id')->references('id')->on('medical_records');
            $table->foreign('patient_id')->references('id')->on('users');
            $table->foreign('doctor_id')->references('id')->on('users');
            $table->foreign('pharmacist_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('prescriptions');
    }
};
