<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = Auth::user();

        // Check if the user has one of the allowed roles

        if($user){

            if ($user->role->roleName != "receptionist" && $user->role->roleName != "doctor" && $user->role->roleName != "hr") {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access.',
                ], 403);
            }
        }else{

            return response()->json([
                'success' => false,
                'message' => 'Not authenticated',
            ],404);
        }

        return $next($request);

    }
}
