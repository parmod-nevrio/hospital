<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // User Management
            [
                'name' => 'View Users',
                'key' => 'users.view',
                'description' => 'Can view user list and details',
                'category' => 'User Management'
            ],
            [
                'name' => 'Create Users',
                'key' => 'users.create',
                'description' => 'Can create new users',
                'category' => 'User Management'
            ],
            [
                'name' => 'Edit Users',
                'key' => 'users.edit',
                'description' => 'Can edit user details',
                'category' => 'User Management'
            ],
            [
                'name' => 'Delete Users',
                'key' => 'users.delete',
                'description' => 'Can delete users',
                'category' => 'User Management'
            ],

            // Role Management
            [
                'name' => 'View Roles',
                'key' => 'roles.view',
                'description' => 'Can view role list and details',
                'category' => 'Role Management'
            ],
            [
                'name' => 'Create Roles',
                'key' => 'roles.create',
                'description' => 'Can create new roles',
                'category' => 'Role Management'
            ],
            [
                'name' => 'Edit Roles',
                'key' => 'roles.edit',
                'description' => 'Can edit role details',
                'category' => 'Role Management'
            ],
            [
                'name' => 'Delete Roles',
                'key' => 'roles.delete',
                'description' => 'Can delete roles',
                'category' => 'Role Management'
            ],

            // Patient Management
            [
                'name' => 'View Patients',
                'key' => 'patients.view',
                'description' => 'Can view patient list and details',
                'category' => 'Patient Management'
            ],
            [
                'name' => 'Create Patients',
                'key' => 'patients.create',
                'description' => 'Can create new patients',
                'category' => 'Patient Management'
            ],
            [
                'name' => 'Edit Patients',
                'key' => 'patients.edit',
                'description' => 'Can edit patient details',
                'category' => 'Patient Management'
            ],
            [
                'name' => 'Delete Patients',
                'key' => 'patients.delete',
                'description' => 'Can delete patients',
                'category' => 'Patient Management'
            ],

            // Appointment Management
            [
                'name' => 'View Appointments',
                'key' => 'appointments.view',
                'description' => 'Can view appointment list and details',
                'category' => 'Appointment Management'
            ],
            [
                'name' => 'Create Appointments',
                'key' => 'appointments.create',
                'description' => 'Can create new appointments',
                'category' => 'Appointment Management'
            ],
            [
                'name' => 'Edit Appointments',
                'key' => 'appointments.edit',
                'description' => 'Can edit appointment details',
                'category' => 'Appointment Management'
            ],
            [
                'name' => 'Delete Appointments',
                'key' => 'appointments.delete',
                'description' => 'Can delete appointments',
                'category' => 'Appointment Management'
            ],

            // Department Management
            [
                'name' => 'View Departments',
                'key' => 'departments.view',
                'description' => 'Can view department list and details',
                'category' => 'Department Management'
            ],
            [
                'name' => 'Create Departments',
                'key' => 'departments.create',
                'description' => 'Can create new departments',
                'category' => 'Department Management'
            ],
            [
                'name' => 'Edit Departments',
                'key' => 'departments.edit',
                'description' => 'Can edit department details',
                'category' => 'Department Management'
            ],
            [
                'name' => 'Delete Departments',
                'key' => 'departments.delete',
                'description' => 'Can delete departments',
                'category' => 'Department Management'
            ],

            // Settings
            [
                'name' => 'View Settings',
                'key' => 'settings.view',
                'description' => 'Can view system settings',
                'category' => 'Settings'
            ],
            [
                'name' => 'Edit Settings',
                'key' => 'settings.edit',
                'description' => 'Can edit system settings',
                'category' => 'Settings'
            ]
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
