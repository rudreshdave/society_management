<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ApiResponse;
use Auth;

class RoleMiddleware
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // dd($roles);
        // Map roles to usertype values
        $roleMap = [
            'superadmin' => 1,
            'admin' => 2,
            'owner' => 3,
            'tenant' => 4,
            'staff' => 5
        ];


        $allowedUserTypes = [];
        //  dd($roles);
        // Check if the role exists in the role map
        foreach ($roles as $role) {
            $role = trim($role);
            if (!isset($roleMap[$role])) {
                return $this->customResponse(10); // Invalid role
            }
            $allowedUserTypes[] = $roleMap[$role];
        }

        //  dd($allowedUserTypes);
        // Check if the user is authenticated and has the required usertype
        // dd($request->user()->usertype);
        if (!Auth::check() || !in_array($request->user()->usertype, $allowedUserTypes)) {

            return $this->customResponse(10);
        }
        return $next($request);
    }
}
