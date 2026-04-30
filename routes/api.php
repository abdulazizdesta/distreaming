<?php

use App\Helpers\ApiMessage;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MovieCategoryController;
use App\Http\Controllers\Api\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("auth/register", [AuthController::class, "registration"]);
Route::post("auth/login", [AuthController::class, "login"]);

Route::middleware(['auth:sanctum', 'role:admin'])->get('/test-admin', function (Request $request) {
    return ApiMessage::success('You are admin!', $request->user(), 200);
});

Route::middleware(["auth:sanctum"])->group(function () {
    Route::get('auth/profile', [AuthController::class, 'profile']);
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Movie Category
    Route::apiResources(["categories" => MovieCategoryController::class]);
    // Movie
    Route::apiResources(["movies" => MovieController::class]);
});