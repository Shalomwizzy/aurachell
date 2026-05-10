<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // Normalise the referral code before validation so the user can type lowercase
        if ($request->filled('referral_code')) {
            $request->merge(['referral_code' => strtoupper(trim($request->referral_code))]);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'referral_code' => [
                'nullable',
                'string',
                'max:12',
                Rule::exists('users', 'referral_code'),
            ],
        ], [
            'referral_code.exists' => 'That referral code is invalid. Please check and try again.',
        ]);

        // Prefer validated form input, fall back to cookie
        $refCode = $request->input('referral_code') ?: strtoupper(trim((string) $request->cookie('ref_code', '')));
        $referrer = $refCode ? User::where('referral_code', $refCode)->first() : null;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'referred_by' => ($referrer && $referrer->email !== $request->email) ? $referrer->id : null,
        ]);

        if ($referrer && $referrer->email !== $request->email) {
            Referral::create([
                'referrer_id' => $referrer->id,
                'referred_id' => $user->id,
                'status' => 'pending',
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        // Redirect to email verification notice — welcome mail sent after verification
        return redirect()->route('verification.notice')
            ->with('status', 'Account created! Please check your email to verify your address.');
    }
}
