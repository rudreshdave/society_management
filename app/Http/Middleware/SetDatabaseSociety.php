<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Artisan;
use App\Helpers\Helper;
use DB,
    Config,
    Auth;

class SetDatabaseSociety
{
    /**
     * @var \App\Helpers\Helper
     */
    private $helper;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $society_id = Auth::user()->currentAccessToken()->abilities['society_id'];
        $society = null;
        foreach (Auth::user()->societies as $society_details) {
            if ($society_details['id'] == $society_id) {
                $society = $society_details;
            }
        }
        if (isset($society->database_name) && !empty($society->database_name) && isset($society->database_username) && !empty($society->database_username) && isset($society->database_password) && !empty($society->database_password)) {
            $this->helper->set_society_wise_database($society->database_name);
        }
        return $next($request);
    }
}
