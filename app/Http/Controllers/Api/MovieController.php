<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiMessage;
use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Storage;
use Validator;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Movie::with('category');
            $per_page = $request->per_page ?? 10;

            if($request->has("search")){
                $query->where("title", "like", "%" . $request->search . "%");
            }

            if ($request->has("sort_by")) {
                $order = $request->order ?? "asc";
                $query->orderBy($request->sort_by, $order);
            } else {
                $query->orderBy("created_at", "desc");
            }

            if ($request->has("category_id")) {
                $query->where("category_id", $request->category_id);
            }

            if ($request->has("release_year")) {
                $query->where("release_year", $request->release_year);
            }

            if ($request->has("year_from") && $request->has("year_to")) {
                $query->whereBetween("release_year", [$request->year_from, $request->year_to]);
            }

            if ($request->has("rating")) {
                $query->where("rating", $request->rating);
            }

            if ($request->has("rating_from") && $request->has("rating_to")) {
                $query->whereBetween("rating", [$request->rating_from, $request->rating_to]);
            }

            $movies = $query->paginate($per_page);
            $response = [
                'meta' => [
                    'current_page' => $movies->currentPage(),
                    'last_page' => $movies->lastPage(),
                    'per_page' => $movies->perPage(),
                    'total' => $movies->total(),
                ],
                'data' => $movies->items(),
            ];

            return ApiMessage::success("Success get data", $response, 200);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return ApiMessage::error("Error internal server", null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            "category_id" => "required|exists:movie_categories,id",
            "title" => "required|string|max:255",
            "description" => "required|string",
            "rating" => "required|numeric|min:0|max:10",
            "release_year" => "required|integer|min:1888|max:" . date("Y"),
            "thumbnail" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
         ];

         $messages = [
            "category_id.required" => "Category ID is required",
            "category_id.exists" => "Category ID must exist",
            "title.required" => "Title is required",
            "title.string" => "Title must be a string",
            "title.max" => "Title must not exceed 255 characters",
            "description.required" => "Description is required",
            "description.string" => "Description must be a string",
            "rating.required" => "Rating is required",
            "rating.numeric" => "Rating must be a number",
            "rating.between" => "Rating must be between 0 and 10",
            "release_year.required" => "Release year is required",
            "release_year.integer" => "Release year must be an integer",
            "release_year.min" => "Release year must be at least 1888",
            "release_year.max" => "Release year must not be greater than the current year",
            "thumbnail.image" => "Thumbnail must be an image file",
            "thumbnail.mimes" => "Thumbnail must be a file of type: jpeg, png, jpg, gif",
            "thumbnail.max" => "Thumbnail size must not exceed 2048 kilobytes",
         ];

         $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
             return ApiMessage::error("Validation failed", $validator->errors(), 422);
         }
         try {
             $data = $request->only("category_id", "title", "description", "rating", "release_year");

             if($request->hasFile("thumbnail")){

                $path = $request->file("thumbnail")->store("movies", "public");
                $data["thumbnail"] = $path; 
             }
             
             $movie = Movie::create($data);

             return ApiMessage::success("Movie created successfully", $movie, 201);
         } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return ApiMessage::error("Error internal server", null, 500);
         }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $movie = Movie::with('category')->find($id);

            if (!$movie) {
                return ApiMessage::error("Movie not found", null, 404);
            }

            return ApiMessage::success("Success get data", $movie, 200);

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return ApiMessage::error("Error internal server", null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            "category_id" => "sometimes|exists:movie_categories,id",
            "title" => "sometimes|string|max:255",
            "description" => "sometimes|string",
            "rating" => "sometimes|numeric|min:0|max:10",
            "release_year" => "sometimes|integer|min:1888|max:" . date("Y"),
            "thumbnail" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
         ];

         $messages = [
            "category_id.exists" => "Category ID must exist",
            "title.string" => "Title must be a string",
            "title.max" => "Title must not exceed 255 characters",
            "description.string" => "Description must be a string",
            "rating.numeric" => "Rating must be a number",
            "rating.between" => "Rating must be between 0 and 10",
            "release_year.integer" => "Release year must be an integer",
            "release_year.min" => "Release year must be at least 1888",
            "release_year.max" => "Release year must not be greater than the current year",
            "thumbnail.image" => "Thumbnail must be an image file",
            "thumbnail.mimes" => "Thumbnail must be a file of type: jpeg, png, jpg, gif",
            "thumbnail.max" => "Thumbnail size must not exceed 2048 kilobytes",
         ];

         $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) {
             return ApiMessage::error("Validation failed", $validator->errors(), 422);
         }
         try {
             $movie = Movie::find($id);

             if (!$movie) {
                 return ApiMessage::error("Movie not found", null, 404);
             }

             $data = $request->only("category_id", "title", "description", "rating", "release_year");
           
             if($request->hasFile("thumbnail")){
                if($movie->thumbnail){
                    Storage::disk("public")->delete($movie->thumbnail);
                }
                $path = $request->file("thumbnail")->store("movies", "public");
                $data["thumbnail"] = $path;
             }

             $movie->update($data);

             return ApiMessage::success("Movie updated successfully", $movie, 200);
         } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return ApiMessage::error("Error internal server", null, 500);
         }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $movie = Movie::find($id);

            if (!$movie) {
                return ApiMessage::error("Movie not found", null, 404);
            }

            if($movie->thumbnail){
                Storage::disk("public")->delete($movie->thumbnail);
            }
            $movie->delete();
            return ApiMessage::success("Movie deleted successfully", null, 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return ApiMessage::error("Error internal server", null, 500);
        }
    }
}
