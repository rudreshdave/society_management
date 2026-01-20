<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\ApiResponse;

class ApiAccessHeaderAuthentication
{
    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (isset($_SERVER['HTTP_USERNAME']) && isset($_SERVER['HTTP_PASSWORD'])) {
            $username = $_SERVER['HTTP_USERNAME'];
            $password = $_SERVER['HTTP_PASSWORD'];
        } else {
            $username = "";
            $password = "";
        }
        if ($username == 'dwarx' && $password == 'dwarx@123') {
            return $next($request);
        } else {
            throw new HttpResponseException($this->customResponse(7));
        }
    }
}
