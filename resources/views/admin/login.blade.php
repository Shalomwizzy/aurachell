<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Aurachell</title>
    @php $favicon = \App\Models\Setting::get('favicon'); @endphp
    @if($favicon)
    <link rel="icon" type="image/png" href="{{ asset('images/' . $favicon) }}">
    @endif
    <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
    <script src="{{ asset('build/assets/app2.js') }}" defer></script>
    <style>:root{--color-primary:#371220;--color-ghost:#C9A96F;--color-bg:#F7F2EB;--color-surface:#FFFFFF;--color-text-dark:#2A2522;--color-text-body:#2A2522;}</style>
</head>
<body class="h-full font-sans antialiased overflow-hidden" style="background:#0d0807;">

{{-- Ambient background layers --}}
<div class="fixed inset-0">
    {{-- Deep base --}}
    <div class="absolute inset-0" style="background:#0d0807;"></div>
    {{-- Top-right warm glow --}}
    <div class="absolute" style="top:-20%;right:-10%;width:60%;height:70%;background:radial-gradient(ellipse,rgba(201,169,111,0.07) 0%,transparent 65%);pointer-events:none;"></div>
    {{-- Bottom-left cool shadow --}}
    <div class="absolute" style="bottom:-20%;left:-10%;width:50%;height:60%;background:radial-gradient(ellipse,rgba(55,18,32,0.60) 0%,transparent 70%);pointer-events:none;"></div>
    {{-- Grain texture --}}
    <div class="absolute inset-0 opacity-[0.035]" style="background-image:url(\"data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E\");pointer-events:none;"></div>
</div>

<div class="relative min-h-screen flex">

    {{-- ═══ LEFT BRAND PANEL ═══ --}}
    <div class="hidden lg:flex lg:w-[45%] xl:w-[42%] flex-col justify-between p-12 xl:p-16 relative overflow-hidden"
         style="border-right:1px solid rgba(201,169,111,0.10);">

        {{-- Decorative vertical line --}}
        <div class="absolute right-0 top-[15%] bottom-[15%] w-px" style="background:linear-gradient(to bottom,transparent,rgba(201,169,111,0.20),transparent);"></div>

        {{-- Logo --}}
        <div>
            @php $logo = \App\Models\Setting::get('logo'); @endphp
            @if($logo)
            <img src="{{ asset('images/' . $logo) }}" alt="Aurachell" class="h-9 mb-2">
            @else
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 flex items-center justify-center flex-shrink-0"
                     style="border:1px solid rgba(201,169,111,0.35);">
                    <div class="w-3 h-3" style="background:rgba(201,169,111,0.65);"></div>
                </div>
                <span class="font-display text-lg tracking-[0.28em] uppercase" style="color:#C9A96F;">Aurachell</span>
            </div>
            @endif
        </div>

        {{-- Centre decorative block --}}
        <div class="flex-1 flex flex-col justify-center">
            {{-- Large ornamental A --}}
            <div class="mb-10 select-none">
                <span class="font-display leading-none select-none"
                      style="font-size:clamp(6rem,12vw,10rem);color:rgba(201,169,111,0.08);letter-spacing:-0.02em;">A</span>
            </div>

            <div class="space-y-6">
                <div class="w-8 h-px" style="background:#C9A96F;"></div>
                <h2 class="font-display text-3xl xl:text-4xl leading-snug" style="color:#F7F2EB;">
                    The art of<br>
                    <em class="not-italic" style="color:#C9A96F;">extraordinary</em><br>
                    fragrance.
                </h2>
                <p class="text-sm leading-relaxed max-w-xs" style="color:rgba(247,242,235,0.38);">
                    Manage your store, curate your catalog, and serve your customers — all from one secure portal.
                </p>
            </div>
        </div>

        {{-- Bottom detail --}}
        <div class="flex items-center gap-3">
            <div class="flex gap-1.5">
                <div class="w-1 h-1 rounded-full" style="background:rgba(201,169,111,0.60);"></div>
                <div class="w-1 h-1 rounded-full" style="background:rgba(201,169,111,0.25);"></div>
                <div class="w-1 h-1 rounded-full" style="background:rgba(201,169,111,0.10);"></div>
            </div>
            <span class="text-[10px] tracking-[0.3em] uppercase" style="color:rgba(247,242,235,0.20);">Authorised access only</span>
        </div>
    </div>

    {{-- ═══ RIGHT FORM PANEL ═══ --}}
    <div class="flex-1 flex flex-col items-center justify-center px-6 py-12 sm:px-12">

        {{-- Mobile logo --}}
        <div class="lg:hidden mb-12 text-center">
            @if(isset($logo) && $logo)
            <img src="{{ asset('images/' . $logo) }}" alt="Aurachell" class="h-8 mx-auto mb-3">
            @else
            <div class="inline-flex items-center gap-2.5 mb-3">
                <div class="w-7 h-7 flex items-center justify-center" style="border:1px solid rgba(201,169,111,0.35);">
                    <div class="w-2.5 h-2.5" style="background:rgba(201,169,111,0.65);"></div>
                </div>
                <span class="font-display text-base tracking-[0.28em] uppercase" style="color:#C9A96F;">Aurachell</span>
            </div>
            @endif
            <p class="text-[9px] tracking-[0.35em] uppercase" style="color:rgba(201,169,111,0.35);">Administration</p>
        </div>

        <div class="w-full max-w-[400px]">

            {{-- Header --}}
            <div class="mb-10">
                <p class="text-[9px] tracking-[0.35em] uppercase mb-3" style="color:rgba(201,169,111,0.50);">Admin Portal</p>
                <h1 class="font-display text-3xl mb-2" style="color:#F7F2EB;">Welcome back</h1>
                <p class="text-xs" style="color:rgba(247,242,235,0.30);">Sign in to continue to your dashboard</p>
            </div>

            {{-- Error --}}
            @if($errors->any())
            <div class="mb-7 flex items-start gap-3 px-4 py-3.5"
                 style="background:rgba(201,169,111,0.07);border:1px solid rgba(201,169,111,0.22);">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="rgba(201,169,111,0.80)" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
                <span class="text-xs" style="color:rgba(247,242,235,0.75);">{{ $errors->first() }}</span>
            </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('admin.login') }}" class="space-y-8">
                @csrf

                {{-- Email --}}
                <div class="space-y-2">
                    <label class="block text-[9px] tracking-[0.3em] uppercase"
                           style="color:rgba(201,169,111,0.55);">Email address</label>
                    <div class="relative">
                        <input type="email" name="email" value="{{ old('email') }}"
                               required autocomplete="email" autofocus
                               placeholder="admin@aurachell.com"
                               class="al-input w-full py-3.5 text-sm transition-all duration-200 focus:outline-none"
                               style="background:transparent;border:none;border-bottom:1px solid rgba(255,255,255,0.08);color:rgba(247,242,235,0.85);padding-left:0;padding-right:0;">
                        <div class="al-underline absolute bottom-0 left-0 h-px w-0 transition-all duration-300" style="background:#C9A96F;"></div>
                    </div>
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <label class="block text-[9px] tracking-[0.3em] uppercase"
                           style="color:rgba(201,169,111,0.55);">Password</label>
                    <div class="relative">
                        <input type="password" name="password"
                               required autocomplete="current-password"
                               placeholder="••••••••"
                               class="al-input w-full py-3.5 text-sm transition-all duration-200 focus:outline-none"
                               style="background:transparent;border:none;border-bottom:1px solid rgba(255,255,255,0.08);color:rgba(247,242,235,0.85);padding-left:0;padding-right:0;">
                        <div class="al-underline absolute bottom-0 left-0 h-px w-0 transition-all duration-300" style="background:#C9A96F;"></div>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="flex items-center gap-2.5">
                    <input type="checkbox" name="remember" id="remember"
                           class="w-3.5 h-3.5 cursor-pointer"
                           style="accent-color:#C9A96F;">
                    <label for="remember" class="text-xs cursor-pointer" style="color:rgba(247,242,235,0.30);">Keep me signed in</label>
                </div>

                {{-- Submit --}}
                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-4 text-xs tracking-[0.3em] uppercase font-semibold transition-all duration-300 relative overflow-hidden group"
                            style="background:#C9A96F;color:#2A1008;">
                        <span class="relative z-10">Access Dashboard</span>
                        <div class="absolute inset-0 translate-y-full group-hover:translate-y-0 transition-transform duration-300"
                             style="background:rgba(201,169,111,0.85);"></div>
                    </button>
                </div>
            </form>

            {{-- Footer note --}}
            <p class="text-center text-[9px] mt-10 tracking-wider" style="color:rgba(247,242,235,0.15);">
                This portal is restricted to authorised staff only.
            </p>
        </div>
    </div>

</div>

<style>
.al-input::placeholder { color: rgba(247,242,235,0.20); }
.al-input:focus + .al-underline,
.al-input:focus ~ .al-underline { width: 100%; }
</style>

<script>
document.querySelectorAll('.al-input').forEach(function(input) {
    var underline = input.nextElementSibling;
    input.addEventListener('focus', function() {
        if (underline) underline.style.width = '100%';
        input.style.borderBottomColor = 'transparent';
    });
    input.addEventListener('blur', function() {
        if (underline) underline.style.width = '0';
        input.style.borderBottomColor = 'rgba(255,255,255,0.08)';
    });
});
</script>

</body>
</html>
