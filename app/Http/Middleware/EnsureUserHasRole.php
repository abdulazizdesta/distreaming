<?php

namespace App\Http\Middleware;

use App\Helpers\ApiMessage;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{

    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        if(!$user){
            return ApiMessage::error('Unauntheticated', null, 401);
        }
        if(!in_array($user->role?->name, $roles)){
            return ApiMessage::error('Forbidden: insufficient role', null, 403);
        }
    
    return $next($request);
    }
}
