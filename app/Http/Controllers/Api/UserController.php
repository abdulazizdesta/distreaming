<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiMessage;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;
use Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = User::query();
            $per_page = $request->per_page ?? 10;

            if ($request->has("search")) {
                $query->where("name", "like", "%" . $request->search . "%")
                      ->orWhere("email", "like", "%" . $request->search . "%");
            }

            $users = $query->paginate($per_page);
            $response = [
                'meta' => [
                    'current_page' => $users->currentPage(),
                    'last_page'    => $users->lastPage(),
                    'per_page'     => $users->perPage(),
                    'total'        => $users->total(),
                ],
                'data' => $users->items(),
            ];

            return ApiMessage::success("Success get data", $response, 200);

        } catch (Throwable $th) {
            Log::error($th->getMessage());
            return ApiMessage::error("Error internal server", null, 500);
        }
    }

    public function show(string $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return ApiMessage::error("User not found", null, 404);
            }

            return ApiMessage::success("Success get data", $user, 200);

        } catch (Throwable $th) {
            Log::error($th->getMessage());
            return ApiMessage::error("Error internal server", null, 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $rules = [
            "name"     => "sometimes|string|max:255",
            "email"    => "sometimes|email|unique:users,email," . $id,
            "role_id"  => "sometimes|exists:roles,id",
            "password" => "sometimes|string|min:8|confirmed",
        ];

        $messages = [
            "name.string"             => "Name must be a string",
            "name.max"                => "Name must not exceed 255 characters",
            "email.email"             => "Email must be a valid email address",
            "email.unique"            => "Email already taken",
            "role_id.exists"          => "Role must exist",
            "password.min"            => "Password must be at least 8 characters",
            "password.confirmed"      => "Password confirmation does not match",
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return ApiMessage::error("Validation failed", $validator->errors(), 422);
        }

        try {
            $user = User::find($id);

            if (!$user) {
                return ApiMessage::error("User not found", null, 404);
            }

            $data = $request->only("name", "email", "role_id");

            if ($request->filled("password")) {
                $data["password"] = Hash::make($request->password);
            }

            $user->update($data);

            return ApiMessage::success("User updated successfully", $user, 200);

        } catch (Throwable $th) {
            Log::error($th->getMessage());
            return ApiMessage::error("Error internal server", null, 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return ApiMessage::error("User not found", null, 404);
            }

            $user->delete();

            return ApiMessage::success("User deleted successfully", null, 200);

        } catch (Throwable $th) {
            Log::error($th->getMessage());
            return ApiMessage::error("Error internal server", null, 500);
        }
    }
}