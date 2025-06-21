<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    public function handle(Request $request, Closure $next)
    {
        $frontendUrl = $request->headers->get('referer');  // fallback to Referer header

        // OR, if you want to use a custom header, uncomment below
        // $frontendUrl = $request->header('X-Frontend-URL');

        Log::info('Incoming request', [
            'method'      => $request->getMethod(),
            'url'         => $request->fullUrl(),
            'frontendUrl' => $frontendUrl,
            'ip'          => $request->ip(),
            'input'       => $request->all(),
        ]);

        return $next($request);
    }
}
