<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);

        // Create default admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@hospital.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('slug', 'admin')->first()->id,
            'is_active' => true
        ]);
    }
}
