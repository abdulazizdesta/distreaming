<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,          // 1. roles dulu
            UserSeeder::class,          // 2. users (butuh roles)
            MovieCategorySeeder::class, // 3. categories
            MovieSeeder::class,         // 4. movies (butuh categories & users)
        ]);
    }
}