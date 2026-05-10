<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Account — Aurachell</title>
    @php $logo = \App\Models\Setting::get('logo'); @endphp
    @if($favicon = \App\Models\Setting::get('favicon'))
    <link rel="icon" href="{{ asset('images/' . $favicon) }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased" style="background:#1a0a06;">

<div class="min-h-screen flex">

    {{-- Left brand panel: deep mahogany → caramel gradient --}}
    <div class="hidden lg:flex lg:w-[45%] relative flex-col justify-between overflow-hidden"
         style="background: linear-gradient(160deg, #4a1510 0%, #2C0F0A 50%, #1a0a06 100%);">

        <div class="absolute inset-0" style="background-image:radial-gradient(ellipse at 80% 10%, rgba(212,184,160,0.10) 0%,transparent 60%),radial-gradient(ellipse at 20% 90%, rgba(196,164,140,0.06) 0%,transparent 60%);"></div>
        <div class="absolute top-1/2 right-0 translate-x-1/3 -translate-y-1/2 w-[500px] h-[500px] rounded-full pointer-events-none" style="border:1px solid rgba(245,237,228,0.07);"></div>
        <div class="absolute bottom-0 left-0 -translate-x-1/3 translate-y-1/3 w-[400px] h-[400px] rounded-full pointer-events-none" style="border:1px solid rgba(245,237,228,0.07);"></div>

        <div class="relative z-10 p-14">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                @if($logo)
                <img src="{{ asset('images/' . $logo) }}" alt="Aurachell" class="h-9">
                @else
                <div class="w-9 h-9 flex items-center justify-center rounded-sm" style="background:rgba(212,184,160,0.15);border:1px solid rgba(212,184,160,0.30);">
                    <span class="font-display text-base font-bold" style="color:#C4A48C;">A</span>
                </div>
                <span class="font-display text-xl tracking-[0.25em] uppercase" style="color:#C4A48C;">Aurachell</span>
                @endif
            </a>
        </div>

        <div class="relative z-10 px-14 space-y-8">
            <div class="w-10 h-px" style="background:rgba(212,184,160,0.45);"></div>
            <p class="font-display text-4xl leading-tight tracking-tight" style="color:#F5EDE4;">
                Begin your<br>olfactory journey.
            </p>
            <p class="font-sans text-sm leading-relaxed max-w-xs" style="color:rgba(245,237,228,0.55);">
                Join thousands of homes that have discovered the transformative power of Aurachell fragrances.
            </p>
        </div>

        <div class="relative z-10 p-14 space-y-3">
            @foreach(['Free delivery on orders over ₦20,000','Exclusive member-only collections','Early access to new launches','Personalised scent recommendations'] as $perk)
            <div class="flex items-center gap-3">
                <div class="w-1 h-1 rounded-full" style="background:#C4A48C;"></div>
                <p class="text-xs font-sans" style="color:rgba(245,237,228,0.65);">{{ $perk }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Right form panel: warm cream --}}
    <div class="flex-1 flex flex-col" style="background:#F5EDE4;">

        <div class="lg:hidden flex items-center px-8 py-7 border-b" style="border-color:rgba(107,32,22,0.10);">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                @if($logo)
                <img src="{{ asset('images/' . $logo) }}" alt="Aurachell" class="h-6">
                @else
                <div class="w-7 h-7 flex items-center justify-center rounded-sm" style="background:#6B2016;">
                    <span class="font-display text-xs font-bold" style="color:#F5EDE4;">A</span>
                </div>
                <span class="font-display text-base tracking-[0.25em] uppercase" style="color:#6B2016;">Aurachell</span>
                @endif
            </a>
        </div>

        <div class="flex-1 flex items-center justify-center px-8 lg:px-14 py-10">
            <div class="w-full max-w-sm">

                <div class="mb-8">
                    <h1 class="font-display text-3xl tracking-tight mb-2" style="color:#2C0F0A;">Create account</h1>
                    <p class="text-sm font-sans" style="color:rgba(44,15,10,0.55);">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-medium underline underline-offset-4 transition-colors" style="color:#6B2016;">Sign in</a>
                    </p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    @php
                    $field = function ($name, $label, $type = 'text', $value = '', $placeholder = '', $extra = '') {
                        return [$name, $label, $type, $value, $placeholder, $extra];
                    };
                    @endphp

                    <div>
                        <label class="block text-[10px] tracking-[0.25em] uppercase mb-2.5" style="color:rgba(44,15,10,0.55);">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                               placeholder="Your full name"
                               class="auth-field"
                               onfocus="this.style.borderColor='#6B2016'" onblur="this.style.borderColor='rgba(107,32,22,0.20)'">
                        @error('name')<p class="text-xs mt-1.5" style="color:#b91c1c;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-[10px] tracking-[0.25em] uppercase mb-2.5" style="color:rgba(44,15,10,0.55);">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                               placeholder="your@email.com"
                               class="auth-field"
                               onfocus="this.style.borderColor='#6B2016'" onblur="this.style.borderColor='rgba(107,32,22,0.20)'">
                        @error('email')<p class="text-xs mt-1.5" style="color:#b91c1c;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-[10px] tracking-[0.25em] uppercase mb-2.5" style="color:rgba(44,15,10,0.55);">
                            Phone <span class="normal-case tracking-normal text-[9px]" style="color:rgba(44,15,10,0.40);">(optional)</span>
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                               placeholder="+234 800 000 0000"
                               class="auth-field"
                               onfocus="this.style.borderColor='#6B2016'" onblur="this.style.borderColor='rgba(107,32,22,0.20)'">
                    </div>

                    <div>
                        <label class="block text-[10px] tracking-[0.25em] uppercase mb-2.5" style="color:rgba(44,15,10,0.55);">Password</label>
                        <input type="password" name="password" required autocomplete="new-password"
                               placeholder="Minimum 8 characters"
                               class="auth-field"
                               onfocus="this.style.borderColor='#6B2016'" onblur="this.style.borderColor='rgba(107,32,22,0.20)'">
                        @error('password')<p class="text-xs mt-1.5" style="color:#b91c1c;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-[10px] tracking-[0.25em] uppercase mb-2.5" style="color:rgba(44,15,10,0.55);">Confirm Password</label>
                        <input type="password" name="password_confirmation" required autocomplete="new-password"
                               placeholder="Repeat your password"
                               class="auth-field"
                               onfocus="this.style.borderColor='#6B2016'" onblur="this.style.borderColor='rgba(107,32,22,0.20)'">
                    </div>

                    <div x-data="{
                            code: '{{ old('referral_code', request()->cookie('ref_code') ?? request('ref', '')) }}',
                            checking: false, valid: null, msg: '',
                            async check() {
                                const v = this.code.trim().toUpperCase();
                                if (!v) { this.valid = null; this.msg = ''; return; }
                                if (v.length < 6) { this.valid = false; this.msg = 'Code too short.'; return; }
                                this.checking = true;
                                try {
                                    const r = await fetch('/referral/check?code=' + encodeURIComponent(v));
                                    const d = await r.json();
                                    this.valid = d.valid;
                                    this.msg   = d.valid ? 'Valid code — referral will be applied.' : 'Code not found. Please check and try again.';
                                } catch(e) { this.valid = null; this.msg = ''; }
                                this.checking = false;
                            }
                        }">
                        <label class="block text-[10px] tracking-[0.25em] uppercase mb-2.5" style="color:rgba(44,15,10,0.55);">
                            Referral Code <span class="normal-case tracking-normal text-[9px]" style="color:rgba(44,15,10,0.40);">(optional)</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="referral_code"
                                   x-model="code"
                                   @blur="check()"
                                   @input="code = $event.target.value.toUpperCase()"
                                   value="{{ old('referral_code', request()->cookie('ref_code') ?? request('ref', '')) }}"
                                   placeholder="Enter a friend's code"
                                   maxlength="12"
                                   class="auth-field uppercase"
                                   :style="valid === true ? 'border-color:#16a34a' : (valid === false ? 'border-color:#b91c1c' : '')"
                                   style="letter-spacing:0.15em;padding-right:28px;"
                                   onfocus="this.style.borderColor='#6B2016'" >
                            {{-- Spinner / tick / cross --}}
                            <span class="absolute right-0 bottom-3 text-xs pointer-events-none" style="line-height:1;">
                                <svg x-show="checking" class="w-4 h-4 animate-spin" style="color:rgba(44,15,10,0.35);" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>
                                <svg x-show="valid === true && !checking" class="w-4 h-4" style="color:#16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <svg x-show="valid === false && !checking" class="w-4 h-4" style="color:#b91c1c;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </span>
                        </div>
                        @error('referral_code')
                        <p class="text-xs mt-1.5" style="color:#b91c1c;">{{ $message }}</p>
                        @else
                        <p class="text-xs mt-1.5" x-show="msg"
                           :style="valid ? 'color:#16a34a' : 'color:#b91c1c'" x-text="msg"></p>
                        @enderror
                    </div>

                    <div class="flex items-start gap-3 pt-2">
                        <input type="checkbox" name="terms" id="terms" required
                               class="w-4 h-4 mt-0.5 cursor-pointer shrink-0" style="accent-color:#6B2016;">
                        <label for="terms" class="text-xs leading-relaxed cursor-pointer" style="color:rgba(44,15,10,0.65);">
                            I agree to the
                            <a href="{{ route('terms') }}" class="font-medium underline underline-offset-4" style="color:#6B2016;">Terms</a>
                            and
                            <a href="{{ route('privacy-policy') }}" class="font-medium underline underline-offset-4" style="color:#6B2016;">Privacy Policy</a>
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full py-4 mt-4 text-xs tracking-[0.25em] uppercase font-medium transition-all duration-300 active:scale-[0.98] rounded-sm"
                            style="background:#6B2016;color:#F5EDE4;"
                            onmouseover="this.style.background='#4a1510'" onmouseout="this.style.background='#6B2016'">
                        Create Account
                    </button>
                </form>

            </div>
        </div>

        <div class="px-8 lg:px-14 py-6 border-t" style="border-color:rgba(107,32,22,0.10);">
            <p class="text-center text-xs" style="color:rgba(44,15,10,0.45);">© {{ date('Y') }} Aurachell. All rights reserved.</p>
        </div>
    </div>
</div>

<style>
.auth-field {
    width: 100%;
    background: transparent;
    border: 0;
    border-bottom: 1px solid rgba(107,32,22,0.20);
    padding: 12px 0;
    font-size: 14px;
    color: #2C0F0A;
    transition: border-color 0.2s ease;
}
.auth-field:focus { outline: none; }
.auth-field::placeholder { color: rgba(44,15,10,0.30); }
</style>

</body>
</html>
