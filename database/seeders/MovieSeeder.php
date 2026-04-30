<?php

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        Movie::insert([
            [
                'category_id'  => 1,
                'title'        => 'The Dark Knight',
                'description'  => 'Batman faces the Joker in Gotham City',
                'rating'       => 9.00,
                'release_year' => 2008,
                'thumbnail'    => null,
                'created_by'   => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'category_id'  => 1,
                'title'        => 'Mad Max: Fury Road',
                'description'  => 'A post-apocalyptic action film',
                'rating'       => 8.10,
                'release_year' => 2015,
                'thumbnail'    => null,
                'created_by'   => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'category_id'  => 2,
                'title'        => 'The Shawshank Redemption',
                'description'  => 'Two imprisoned men bond over years finding solace',
                'rating'       => 9.30,
                'release_year' => 1994,
                'thumbnail'    => null,
                'created_by'   => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'category_id'  => 3,
                'title'        => 'The Grand Budapest Hotel',
                'description'  => 'A writer encounters the lobby boy of a famous hotel',
                'rating'       => 8.10,
                'release_year' => 2014,
                'thumbnail'    => null,
                'created_by'   => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'category_id'  => 4,
                'title'        => 'Get Out',
                'description'  => 'A young African-American visits his white girlfriend\'s family',
                'rating'       => 7.70,
                'release_year' => 2017,
                'thumbnail'    => null,
                'created_by'   => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'category_id'  => 5,
                'title'        => 'Interstellar',
                'description'  => 'A team of explorers travel through a wormhole in space',
                'rating'       => 8.70,
                'release_year' => 2014,
                'thumbnail'    => null,
                'created_by'   => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);
    }
}