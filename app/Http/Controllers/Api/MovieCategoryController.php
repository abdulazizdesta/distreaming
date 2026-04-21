<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiMessage;
use App\Http\Controllers\Controller;
use App\Models\MovieCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class MovieCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $query = MovieCategory::query();

            if($request->has("search")) {
                $query->where("name", "like", "%" . $request->search . "%")
                      ->orWhere("description", "like", "%" . $request->search . "%");
            }

            if($request->has("sort_by")){
                $order = $request->order ?? "asc";
                $query->orderBy($request->sort_by, $order );
            }else{
                $query->orderBy("created_at", "desc");
            }

            $per_page = $request->per_page ?? 10; 
            $movieCategory = $query->paginate($per_page);
            $response = [
                'meta' => [
                    'current_page' => $movieCategory->currentPage(),
                    'last_page'=> $movieCategory->lastPage(),
                    'per_page'=> $movieCategory->perPage(),
                    'total'=> $movieCategory->total(),
                ],
                'data'=> $movieCategory->items(),
            ];

            return ApiMessage::success("Succes", $response, 200);

        }catch(Throwable $th){
            Log::error($th->getMessage());
            return ApiMessage::error("Error internal server", null, 500);
        };
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            "name" => "required|string|max:255|unique:movie_categories,name",
            "description" => "nullable|string",
        ];

        $messages = [
            "name.required" => "Name is required",
            "name.string" => "Name must be a string",
            "name.unique" => "Name has already been taken",
            "name.max" => "Name must not exceed 255 characters",
            "description.string" => "Description must be a string",
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return ApiMessage::error("Data validation failed", $validator->errors(), 422);
        }

        try{
            $movieCategory = MovieCategory::create($request->only("name", "description"));
            return ApiMessage::success("Movie category created successfully", $movieCategory, 201);
        }catch(Throwable $th){
            Log::error($th->getMessage());
            return ApiMessage::error("Error internal server", null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $movieCategory = MovieCategory::with('movies')->find($id);

            if(!$movieCategory){
                return ApiMessage::error("Movie category not found", null, 404);
            }

            return ApiMessage::success("Success", $movieCategory, 200);

        }catch(Throwable $th){
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
            "name" => "sometimes|string|max:255|unique:movie_categories,name," . $id,
            "description" => "nullable|string",
        ];

        $messages = [
            "name.required" => "Name is required",
            "name.string" => "Name must be a string",
            "name.unique" => "Name has already been taken",
            "name.max" => "Name must not exceed 255 characters",
            "description.string" => "Description must be a string",
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return ApiMessage::error("Data validation failed", $validator->errors(), 422);
        }

        try{
            $movieCategory = MovieCategory::find($id);

            if(!$movieCategory){
                return ApiMessage::error("Movie category not found", null, 404);
            }

            $movieCategory->update($request->only("name", "description"));
            return ApiMessage::success("Movie category updated successfully", $movieCategory, 200);
        }catch(Throwable $th){
            Log::error($th->getMessage());
            return ApiMessage::error("Error internal server", null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Soft Delete
        try{
            $movieCategory = MovieCategory::find($id);

            if(!$movieCategory){
                return ApiMessage::error("Movie category not found", null, 404);
            }

            $movieCategory->delete();
            return ApiMessage::success("Movie category deleted successfully", null, 200);
        }catch(Throwable $th){
            Log::error($th->getMessage());
            return ApiMessage::error("Error internal server", null, 500);
        }
    }
}
