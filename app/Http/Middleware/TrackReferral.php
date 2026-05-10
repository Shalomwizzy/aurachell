<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackReferral
{
    public function handle(Request $request, Closure $next): Response
    {
        $ref = $request->query('ref');

        if ($ref && ! $request->hasCookie('ref_code')) {
            $response = $next($request);

            return $response->withCookie(
                cookie('ref_code', strtoupper($ref), 60 * 24 * 30) // 30 days
            );
        }

        return $next($request);
    }
}
