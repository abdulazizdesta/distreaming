<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() : void{
        Schema::create("movies", function (Blueprint $table) {
            $table->increments("id");
            $table->foreignId("category_id")->constrained("movie_categories")->onDelete("restrict");
            $table->string("title");
            $table->string("description");
            $table->decimal("rating", 3, 2);
            $table->integer('release_year');
            $table->string('thumbnail')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Rollback the migrations.
     */
    public function down() : void{
        Schema::dropIfExists('movies');
    }
};
