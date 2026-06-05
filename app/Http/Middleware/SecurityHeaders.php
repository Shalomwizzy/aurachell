<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Content-Security-Policy — locks down resource origins while supporting
        // Alpine.js inline handlers, Paystack/Flutterwave iframes, GA, OneSignal.
        // 'unsafe-inline' is required for Alpine.js x-on directives and inline
        // Blade styles; it limits reflected-XSS protection but all other
        // directives (object-src, base-uri, form-action) remain fully enforced.
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://js.paystack.co https://checkout.flutterwave.com https://www.googletagmanager.com https://www.google-analytics.com https://cdn.onesignal.com https://onesignal.com",
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data: https: blob:",
            "connect-src 'self' https://api.paystack.co https://checkout.paystack.com https://www.google-analytics.com https://analytics.google.com https://onesignal.com https://*.onesignal.com https://api.groq.com https://generativelanguage.googleapis.com",
            "font-src 'self' data:",
            "frame-src 'self' https://js.paystack.co https://checkout.paystack.com https://checkout.flutterwave.com https://www.googletagmanager.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
