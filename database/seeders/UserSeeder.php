<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name'       => 'Admin diStreaming',
                'email'      => 'admin@distreaming.com',
                'password'   => Hash::make('password123'),
                'role_id'    => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'John Doe',
                'email'      => 'john@distreaming.com',
                'password'   => Hash::make('password123'),
                'role_id'    => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Jane Doe',
                'email'      => 'jane@distreaming.com',
                'password'   => Hash::make('password123'),
                'role_id'    => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}