<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpBasicAuthenticationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $credentials = $request->header('Authorization');
       // dd($credentials);
        if (!$credentials) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        //dd($request);
        // Extracting username and password from headers
        //list($username, $password) = explode(':', base64_decode(substr($credentials, 6)));

        // // Attempt to authenticate the user
        // if (!Auth::attempt(['email' => $username, 'password' => $password])) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        return $next($request);
    }
}
