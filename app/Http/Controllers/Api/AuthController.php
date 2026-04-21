<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiMessage;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AuthController extends Controller
{
    public function registration(Request $request){
        $rules = [
            "email"=> "required|email",
            "password"=> "required|string|min:8",
        ];
        $messages = [
            "email.required"=> "Email is required",
            "email.email"=> "Email must be a valid email address",
            "password.required"=> "Password is required",
            "password.string"=> "Password must be a string",
            "password.min"=> "Password must be at least 8 characters",

        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()) {
            return ApiMessage::error("Error", $validator->errors(), 422);
        }
        try{
            $user = User::create($request->all());
            return ApiMessage::success("User created successfully", $user, 201);

        }catch(Throwable $th){
            Log::error($th->getMessage());
            return ApiMessage::error("Error internal server", null, 500);
        }
    }

    public function login(Request $request){
        $rules = [
            "email"=> "required|email",
            "password"=> "required|string",
        ];
        $messages = [
            "email.required"=> "Email is required",
            "email.email"=> "Email must be a valid email address",
            "password.required"=> "Password is required",
            "password.string"=> "Password must be a string",
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()) {
            return ApiMessage::error("Error", $validator->errors(), 422);
        }
        try{
            $user = User::where("email", $request->email)->first();
            if(!$user || !\Hash::check($request->password, $user->password)){
                return ApiMessage::error("Email or password is invalid", null, 401);
            }
            $token = $user->createToken("auth_token")->plainTextToken;
            return ApiMessage::success("User logged in successfully", ["token" => $token], 200);

        }catch(Throwable $th){
            Log::error($th->getMessage());
            return ApiMessage::error("Error internal server", null, 500);
        }
    }

    public function logout(Request $request){
        try{
            $request->user()->currentAccessToken()->delete();
            return ApiMessage::success("User logged out successfully", null, 200);

        }catch(Throwable $th){
            Log::error($th->getMessage());
            return ApiMessage::error("Terjadi kesalahan server", null, 500);
        }
    }

    public function profile(Request $request){
        try{
            return ApiMessage::success("Succes get User Profile", $request->user(), 200);
        }catch(Throwable $th){
            Log::error($th->getMessage());
            return ApiMessage::error("Error internal server", null, 500);
        }
    }
}
