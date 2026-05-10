@extends('layouts.account')
@section('title', 'My Profile')
@section('account-content')
<h1 class="font-display text-2xl text-text-dark mb-6">My Profile</h1>
<div class="bg-white shadow-luxury p-6">
    <form action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="input-luxury" required>
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Phone</label>
                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" class="input-luxury">
            </div>
        </div>
        <div>
            <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Email</label>
            <input type="email" value="{{ $user->email }}" class="input-luxury opacity-60" disabled>
            <p class="text-xs text-text-muted mt-1">Email cannot be changed. Contact support for help.</p>
        </div>
        <div class="border-t border-sand/50 pt-5">
            <h3 class="font-display text-base mb-4">Change Password</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Current Password</label>
                    <input type="password" name="current_password" class="input-luxury">
                    @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">New Password</label>
                    <input type="password" name="password" class="input-luxury">
                </div>
                <div>
                    <label class="block text-xs tracking-widest uppercase text-text-muted mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="input-luxury">
                </div>
            </div>
        </div>
        <button type="submit" class="btn-primary">Save Changes</button>
    </form>
</div>
@endsection
