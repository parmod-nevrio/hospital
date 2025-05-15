<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('medical_record_id')->nullable();
            $table->string('test_type');
            $table->text('test_description');
            $table->enum('status', ['ordered', 'sample_collected', 'processing', 'completed', 'cancelled']);
            $table->unsignedBigInteger('technician_id')->nullable();
            $table->json('results')->nullable();
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('users');
            $table->foreign('doctor_id')->references('id')->on('users');
            $table->foreign('medical_record_id')->references('id')->on('medical_records');
            $table->foreign('technician_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lab_tests');
    }
};
