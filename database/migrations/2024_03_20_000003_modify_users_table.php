<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->after('id')->default(1); // Make sure this ID exists
            $table->string('phone')->nullable()->after('email');
            $table->string('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('employee_id')->nullable()->unique();
            $table->boolean('is_active')->default(true);
            $table->text('specialization')->nullable(); // For doctors
            $table->text('qualification')->nullable();
            $table->string('blood_group')->nullable();
            $table->text('emergency_contact')->nullable();
            $table->json('meta_data')->nullable(); // For additional role-specific data

            // Foreign key constraints
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('restrict');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['department_id']);
            $table->dropColumn([
                'role_id',
                'phone',
                'address',
                'date_of_birth',
                'gender',
                'department_id',
                'employee_id',
                'is_active',
                'specialization',
                'qualification',
                'blood_group',
                'emergency_contact',
                'meta_data'
            ]);
        });
    }
};
