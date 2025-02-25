<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;  // Ensure the User model is imported

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database with users.
     *
     * @return void
     */
    public function run()
    {
       
        // Or create a specific user manually
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);
    }
}
