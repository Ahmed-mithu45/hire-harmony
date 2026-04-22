<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // updateOrCreate prevents duplicate entries if you run the seeder twice
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Search criteria
            [
                'name'      => 'System Admin',
                'password'  => Hash::make('1234admin'),
                'role'      => 'admin',
                'user_type' => 'admin',
                'unique_id' => 'ADM-' . date('Y') . '-' . rand(1000, 9999),
            ]
        );
    }
}
