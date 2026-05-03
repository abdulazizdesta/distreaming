<?php

namespace App\Models;

use App\Models\MovieCategory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        "created_by",
    ];

    public function category(){
        return $this->belongsTo(MovieCategory::class, 'category_id' );
    }

    protected function ratingClass() : Attribute{
        
        return Attribute::make(
            get: function() {
                if($this->rating >= 8.5){
                    return "Top rated";
                }elseif($this->rating >= 7.0){
                    return "Popular";
                }else{
                    return "Regular";
                }
            }
        );
    }

    protected $appends = ['rating_class'];

    public function creator():BelongsTo
    { 
        return $this->belongsTo(User::class, "created_by");
    }
    
}
