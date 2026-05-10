<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class HoneypotCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->filled('website')) {
            Log::info('Honeypot triggered', ['ip' => $request->ip(), 'url' => $request->url()]);

            // Return a fake success so bots don't know they were caught
            return back()->with('success', 'Thank you!');
        }

        return $next($request);
    }
}
