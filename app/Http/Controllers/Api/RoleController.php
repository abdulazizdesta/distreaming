<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Throwable;

class RoleController extends Controller
{
    public function store(Request $request) {
        try{
            $query = Role::query();

            if($request->has("search")) {
                $query->where("name", "like", "%" . $request->search . "%");
            }

        }catch(Throwable $th){

        }
    }
}
