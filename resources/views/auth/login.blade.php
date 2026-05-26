<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In — Aurachell</title>
    @php $logo = \App\Models\Setting::get('logo'); @endphp
    @if($favicon = \App\Models\Setting::get('favicon'))
    <link rel="icon" href="{{ asset('images/' . $favicon) }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased" style="background:#1a0a06;">

<div class="min-h-screen flex">

    {{-- Left brand panel: deep mahogany with caramel accents --}}
    <div class="hidden lg:flex lg:w-[55%] relative flex-col justify-between overflow-hidden"
         style="background: linear-gradient(145deg, #1E0C14 0%, #220B14 50%, #1a0a06 100%);">

        {{-- Texture --}}
        <div class="absolute inset-0" style="background-image:radial-gradient(ellipse at 20% 20%, rgba(201,169,111,0.10) 0%,transparent 60%),radial-gradient(ellipse at 80% 80%, rgba(212,185,154,0.06) 0%,transparent 60%);"></div>

        {{-- Decorative rings --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none">
            <div class="w-[700px] h-[700px] rounded-full" style="border:1px solid rgba(212,185,154,0.06);"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-[500px] h-[500px] rounded-full" style="border:1px solid rgba(212,185,154,0.10);"></div>
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-[320px] h-[320px] rounded-full" style="border:1px solid rgba(212,185,154,0.16);"></div>
            </div>
        </div>

        {{-- Top --}}
        <div class="relative z-10 p-14">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                @if($logo)
                <img src="{{ asset('images/' . $logo) }}" alt="Aurachell" class="h-9">
                @else
                <div class="w-9 h-9 flex items-center justify-center rounded-sm" style="background:rgba(212,185,154,0.15);border:1px solid rgba(212,185,154,0.30);">
                    <span class="font-display text-base font-bold" style="color:#C9A96F;">A</span>
                </div>
                <span class="font-display text-xl tracking-[0.25em] uppercase transition-colors" style="color:#C9A96F;">Aurachell</span>
                @endif
            </a>
        </div>

        {{-- Centre --}}
        <div class="relative z-10 px-14 space-y-8">
            <div class="w-10 h-px" style="background:rgba(212,185,154,0.45);"></div>
            <div>
                <p class="font-sans text-[10px] tracking-[0.35em] uppercase mb-5" style="color:rgba(212,185,154,0.55);">Home Fragrance</p>
                <h2 class="font-display text-5xl leading-tight tracking-tight" style="color:#FAF5ED;">
                    Where scent<br>becomes ritual.
                </h2>
            </div>
            <p class="font-sans text-sm leading-relaxed max-w-xs" style="color:rgba(250,245,237,0.55);">
                Luxury home diffusers, thoughtfully crafted from the world's finest botanicals.
            </p>
        </div>

        {{-- Bottom stats --}}
        <div class="relative z-10 p-14 flex items-center gap-10">
            @foreach([['5K+','Happy Homes'],['48h','Delivery'],['100%','Natural']] as [$val,$label])
            <div>
                <p class="font-display text-2xl" style="color:#C9A96F;">{{ $val }}</p>
                <p class="text-[10px] tracking-[0.2em] uppercase mt-0.5" style="color:rgba(250,245,237,0.40);">{{ $label }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Right form panel: warm cream with mahogany form --}}
    <div class="flex-1 flex flex-col" style="background:#FAF5ED;">

        {{-- Mobile logo --}}
        <div class="lg:hidden flex items-center px-8 py-7 border-b" style="border-color:rgba(55,18,32,0.10);">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                @if($logo)
                <img src="{{ asset('images/' . $logo) }}" alt="Aurachell" class="h-6">
                @else
                <div class="w-7 h-7 flex items-center justify-center rounded-sm" style="background:#371220;">
                    <span class="font-display text-xs font-bold" style="color:#FAF5ED;">A</span>
                </div>
                <span class="font-display text-base tracking-[0.25em] uppercase" style="color:#371220;">Aurachell</span>
                @endif
            </a>
        </div>

        <div class="flex-1 flex items-center justify-center px-8 lg:px-16 py-12">
            <div class="w-full max-w-sm">

                <div class="mb-10">
                    <h1 class="font-display text-3xl tracking-tight mb-2" style="color:#1E0C14;">Welcome back</h1>
                    <p class="text-sm font-sans" style="color:rgba(30,12,20,0.55);">
                        New to Aurachell?
                        <a href="{{ route('register') }}" class="font-medium underline underline-offset-4 transition-colors" style="color:#371220;">Create account</a>
                    </p>
                </div>

                @if(session('status'))
                <div class="mb-6 px-4 py-3 border-l-2 text-sm" style="background:rgba(55,18,32,0.06);border-color:#371220;color:#371220;">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-[10px] tracking-[0.25em] uppercase mb-3" style="color:rgba(30,12,20,0.55);">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                               placeholder="your@email.com"
                               class="w-full bg-transparent border-0 border-b py-3 text-sm focus:outline-none transition-colors duration-200"
                               style="border-color:rgba(55,18,32,0.20);color:#1E0C14;"
                               onfocus="this.style.borderColor='#371220'"
                               onblur="this.style.borderColor='rgba(55,18,32,0.20)'">
                        @error('email')<p class="text-xs mt-2" style="color:#b91c1c;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-[10px] tracking-[0.25em] uppercase" style="color:rgba(30,12,20,0.55);">Password</label>
                            @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[10px] tracking-wider underline underline-offset-4 transition-colors" style="color:rgba(55,18,32,0.65);">Forgot?</a>
                            @endif
                        </div>
                        <input type="password" name="password" required autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full bg-transparent border-0 border-b py-3 text-sm focus:outline-none transition-colors duration-200"
                               style="border-color:rgba(55,18,32,0.20);color:#1E0C14;"
                               onfocus="this.style.borderColor='#371220'"
                               onblur="this.style.borderColor='rgba(55,18,32,0.20)'">
                        @error('password')<p class="text-xs mt-2" style="color:#b91c1c;">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="remember" id="remember"
                               class="w-4 h-4 cursor-pointer" style="accent-color:#371220;">
                        <label for="remember" class="text-xs cursor-pointer select-none" style="color:rgba(30,12,20,0.65);">Keep me signed in</label>
                    </div>

                    <button type="submit"
                            class="w-full py-4 mt-2 text-xs tracking-[0.25em] uppercase font-medium transition-all duration-300 active:scale-[0.98] rounded-sm"
                            style="background:#371220;color:#FAF5ED;"
                            onmouseover="this.style.background='#220B14'" onmouseout="this.style.background='#371220'">
                        Sign In
                    </button>
                </form>

            </div>
        </div>

        <div class="px-8 lg:px-16 py-6 border-t" style="border-color:rgba(55,18,32,0.10);">
            <p class="text-center text-xs" style="color:rgba(30,12,20,0.45);">© {{ date('Y') }} Aurachell. All rights reserved.</p>
        </div>
    </div>
</div>

</body>
</html>
