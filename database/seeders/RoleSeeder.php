<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Controls multiple hospital branches and has full system access'
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Manages the entire hospital portal'
            ],
            [
                'name' => 'Doctor',
                'slug' => 'doctor',
                'description' => 'Medical professional who treats patients'
            ],
            [
                'name' => 'Patient',
                'slug' => 'patient',
                'description' => 'Registered patient in the hospital'
            ],
            [
                'name' => 'Receptionist',
                'slug' => 'receptionist',
                'description' => 'Front desk staff managing appointments and registrations'
            ],
            [
                'name' => 'Nurse',
                'slug' => 'nurse',
                'description' => 'Medical staff assisting doctors and caring for patients'
            ],
            [
                'name' => 'Pharmacist',
                'slug' => 'pharmacist',
                'description' => 'Manages medications and prescriptions'
            ],
            [
                'name' => 'Lab Technician',
                'slug' => 'lab-technician',
                'description' => 'Conducts and manages laboratory tests'
            ],
            [
                'name' => 'Accountant',
                'slug' => 'accountant',
                'description' => 'Manages hospital finances and billing'
            ],
            [
                'name' => 'Radiologist',
                'slug' => 'radiologist',
                'description' => 'Specialist in medical imaging'
            ],
            [
                'name' => 'Department Head',
                'slug' => 'department-head',
                'description' => 'Manages specific hospital department'
            ],
            [
                'name' => 'Insurance Agent',
                'slug' => 'insurance-agent',
                'description' => 'Handles insurance claims and verifications'
            ],
            [
                'name' => 'Maintenance Staff',
                'slug' => 'maintenance',
                'description' => 'Handles hospital maintenance and cleaning'
            ],
            [
                'name' => 'IT Support',
                'slug' => 'it-support',
                'description' => 'Manages technical systems and support'
            ]
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'name' => $role['name'],
                'slug' => $role['slug'],
                'description' => $role['description'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
