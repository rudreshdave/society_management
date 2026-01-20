<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPostFileSize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       
        $maxSize = 1024; // 10 MB
        if ($request->server('CONTENT_LENGTH') > $maxSize) {
            return response('Payload too large', 413);
        }
        return dd("Middleware is working.");
        return $next($request);
    }
    
}
