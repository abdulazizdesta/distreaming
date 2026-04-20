<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "category_id",
        "title",
        "description",
        "rating",
        "release_year",
        "thumbnail",
    ];

    public function category(){
        return $this->belongsTo(MovieCategory::class, 'category_id' );
    }
    
}
