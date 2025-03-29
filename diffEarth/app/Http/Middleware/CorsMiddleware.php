<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Log;

class CorsMiddleware
{
/**
* Handle an incoming request.
*
* @param Request $request
* @param Closure $next
* @return mixed
*/
    public function handle(Request $request, Closure $next)
    {
        Log::info('CORS middleware reached');
        if ($request->getMethod() == "OPTIONS") {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', '*') // Adjust for your allowed origin
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        return $next($request)
            ->header('Access-Control-Allow-Origin', '*') // Adjust for your allowed origin
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization')
            ->header('Access-Control-Allow-Credentials', 'true');
    }
}
