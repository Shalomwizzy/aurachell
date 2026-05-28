<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Aurachell') — Account</title>
        @php $logo = \App\Models\Setting::get('logo'); @endphp
        @if($favicon = \App\Models\Setting::get('favicon'))
        <link rel="icon" href="{{ asset('images/' . $favicon) }}">
        @endif

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .auth-input {
                width: 100%;
                background: transparent;
                border: 0;
                border-bottom: 1px solid rgba(55,18,32,0.20);
                padding: 12px 0;
                font-size: 14px;
                color: #371220;
                transition: border-color 0.2s ease;
            }
            .auth-input:focus { outline: none; border-color: #371220; }
            .auth-input::placeholder { color: rgba(55,18,32,0.30); }

            .auth-label {
                display: block;
                font-size: 10px;
                letter-spacing: 0.25em;
                text-transform: uppercase;
                margin-bottom: 10px;
                color: rgba(55,18,32,0.55);
            }

            .auth-btn {
                display: inline-block;
                padding: 14px 32px;
                font-size: 11px;
                letter-spacing: 0.25em;
                text-transform: uppercase;
                font-weight: 500;
                background: #371220;
                color: #FAF5ED;
                border-radius: 2px;
                transition: background 0.2s, transform 0.05s;
                border: none;
                cursor: pointer;
            }
            .auth-btn:hover { background: #371220; }
            .auth-btn:active { transform: scale(0.98); }
            .auth-btn-block { width: 100%; }

            .auth-error { color: #371220; font-size: 12px; margin-top: 6px; }
            .auth-link { color: #371220; text-decoration: underline; text-underline-offset: 4px; font-weight: 500; }
        </style>
    </head>
    <body class="font-sans antialiased min-h-screen" style="background:#FAF5ED;color:#371220;">

        <div class="min-h-screen flex flex-col items-center justify-center px-6 py-12">

            <a href="{{ route('home') }}" class="mb-10 flex items-center gap-3">
                @if($logo)
                <img src="{{ asset('images/' . $logo) }}" alt="Aurachell" class="h-10">
                @else
                <div class="w-10 h-10 flex items-center justify-center rounded-sm" style="background:#371220;">
                    <span class="font-display text-base font-bold" style="color:#FAF5ED;">A</span>
                </div>
                <span class="font-display text-xl tracking-[0.25em] uppercase" style="color:#C9A96F;">Aurachell</span>
                @endif
            </a>

            <div class="w-full max-w-md p-8 sm:p-10 rounded-sm" style="background:#ffffff;border:1px solid rgba(55,18,32,0.10);box-shadow:0 4px 30px rgba(55,18,32,0.07);">
                {{ $slot }}
            </div>

            <p class="mt-8 text-xs" style="color:rgba(201,169,111,0.45);">© {{ date('Y') }} Aurachell. All rights reserved.</p>
        </div>

    </body>
</html>
