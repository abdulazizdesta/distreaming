<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovieCategory extends Model
{
    use SoftDeletes;

    protected $table = "movie_categories";

    protected $fillable = [
        "name",
        "description",
    ];

    public function movies()
    { 
        return $this->hasMany(Movie::class, "category_id");
    }
}
