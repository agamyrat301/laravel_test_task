<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiRequestLogger
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        $response = $next($request);

        Log::channel('api_requests')->info('API Request', [
            'method'      => $request->method(),
            'path'        => $request->path(),
            'ip'          => $request->ip(),
            'status'      => $response->getStatusCode(),
            'duration_ms' => (int) round((microtime(true) - $start) * 1000),
            'user_agent'  => $request->userAgent(),
        ]);

        return $response;
    }
}
