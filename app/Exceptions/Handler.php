<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (UnauthorizedException $_, $request) {
            if ($request->is('admin/*') || $request->is('admin')) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'You do not have permission to access that section.');
            }
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
