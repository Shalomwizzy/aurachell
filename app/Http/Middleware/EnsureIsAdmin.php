<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Session expired. Please log in again.'], 401);
            }

            return redirect()->route('admin.login');
        }

        if (! auth()->user()->hasAnyRole(['super_admin', 'admin', 'sales_rep', 'fulfillment', 'inventory_manager', 'support'])) {
            auth()->logout();
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Access denied.'], 403);
            }

            return redirect()->route('admin.login')->withErrors(['email' => 'You do not have admin access.']);
        }

        return $next($request);
    }
}
