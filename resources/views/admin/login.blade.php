<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Aurachell</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased" style="background:#0d1117;">

<div class="min-h-screen flex items-center justify-center px-4">

    {{-- Subtle background pattern --}}
    <div class="fixed inset-0 pointer-events-none" style="background: radial-gradient(ellipse at 30% 20%, rgba(47,74,58,0.15) 0%, transparent 60%), radial-gradient(ellipse at 70% 80%, rgba(139,111,71,0.08) 0%, transparent 60%);"></div>

    <div class="relative w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-12">
            @php $logo = \App\Models\Setting::get('logo'); @endphp
            @if($logo)
            <img src="{{ asset('images/' . $logo) }}" alt="Aurachell" class="h-10 mx-auto mb-4">
            @else
            <div class="inline-flex items-center gap-3 mb-4">
                <div class="w-9 h-9 border border-[#D9C5A0]/30 flex items-center justify-center">
                    <div class="w-3.5 h-3.5 bg-[#D9C5A0]/50"></div>
                </div>
                <span class="font-display text-xl tracking-[0.25em] uppercase" style="color:#D9C5A0;">Aurachell</span>
            </div>
            @endif
            <p class="text-[10px] tracking-[0.3em] uppercase mt-2" style="color:rgba(255,255,255,0.2);">Administration</p>
        </div>

        {{-- Card --}}
        <div style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.06);" class="p-10">

            <h1 class="font-display text-2xl mb-1" style="color:rgba(250,247,242,0.85);">Sign In</h1>
            <p class="text-xs mb-8" style="color:rgba(255,255,255,0.25);">Authorised personnel only</p>

            @if($errors->any())
            <div class="mb-6 px-4 py-3 text-sm" style="background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.2); color:#f87171;">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-7">
                @csrf

                <div>
                    <label class="block text-[10px] tracking-[0.25em] uppercase mb-3" style="color:rgba(255,255,255,0.25);">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                           placeholder="admin@aurachell.com"
                           style="background:rgba(255,255,255,0.04); border:none; border-bottom:1px solid rgba(255,255,255,0.1);"
                           class="w-full px-0 py-3 text-sm focus:outline-none transition-colors duration-200"
                           style="color:rgba(250,247,242,0.85); placeholder-color:rgba(250,247,242,0.15);"
                           onfocus="this.style.borderBottomColor='rgba(217,197,160,0.4)'"
                           onblur="this.style.borderBottomColor='rgba(255,255,255,0.1)'">
                </div>

                <div>
                    <label class="block text-[10px] tracking-[0.25em] uppercase mb-3" style="color:rgba(255,255,255,0.25);">Password</label>
                    <input type="password" name="password" required autocomplete="current-password"
                           placeholder="••••••••"
                           style="background:rgba(255,255,255,0.04); border:none; border-bottom:1px solid rgba(255,255,255,0.1);"
                           class="w-full px-0 py-3 text-sm focus:outline-none transition-colors duration-200"
                           onfocus="this.style.borderBottomColor='rgba(217,197,160,0.4)'"
                           onblur="this.style.borderBottomColor='rgba(255,255,255,0.1)'">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="remember"
                               class="w-3.5 h-3.5 border border-white/20 accent-sage bg-transparent cursor-pointer">
                        <span class="text-xs" style="color:rgba(255,255,255,0.3);">Remember me</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-4 text-xs tracking-[0.25em] uppercase font-medium transition-all duration-300 hover:opacity-90 active:scale-[0.98]"
                        style="background:#2F4A3A; color:#FAF7F2;">
                    Access Dashboard
                </button>
            </form>
        </div>

        <p class="text-center text-[10px] mt-8 tracking-wider" style="color:rgba(255,255,255,0.15);">
            This portal is for authorised staff only.
        </p>

    </div>
</div>

<style>
input::placeholder { color: rgba(250,247,242,0.2); }
input { color: rgba(250,247,242,0.85); }
</style>
</body>
</html>
