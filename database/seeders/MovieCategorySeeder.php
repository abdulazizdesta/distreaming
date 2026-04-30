<?php

namespace Database\Seeders;

use App\Models\MovieCategory;
use Illuminate\Database\Seeder;

class MovieCategorySeeder extends Seeder
{
    public function run(): void
    {
        MovieCategory::insert([
            [
                'name'        => 'Action',
                'description' => 'High energy movies with lots of physical stunts and fights',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Drama',
                'description' => 'Character-driven stories with emotional themes',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Comedy',
                'description' => 'Movies designed to make audiences laugh',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Horror',
                'description' => 'Movies designed to frighten and disturb',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'Sci-Fi',
                'description' => 'Science fiction and futuristic themes',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}