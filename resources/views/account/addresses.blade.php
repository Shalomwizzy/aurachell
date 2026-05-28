@extends('layouts.account')
@section('title', 'My Addresses')
@section('account-content')
<div x-data="{ showForm: false, editId: null, editData: {} }">

<div class="flex items-center justify-between mb-6">
    <h1 class="font-display text-2xl text-text-dark">Saved Addresses</h1>
    <button @click="showForm = !showForm; editId = null; editData = {}" class="btn-primary text-xs py-2 px-5">
        + Add Address
    </button>
</div>

@if(session('success'))
<div class="mb-5 px-4 py-3 text-sm font-sans bg-mahogany/10 border border-mahogany/30 text-mahogany">{{ session('success') }}</div>
@endif

{{-- Add / Edit Form --}}
<div x-show="showForm" x-transition class="bg-white shadow-luxury p-6 mb-6">
    <h2 class="font-display text-lg mb-5" x-text="editId ? 'Edit Address' : 'New Address'"></h2>

    {{-- Add form --}}
    <form id="add-address-form" method="POST" action="{{ route('account.addresses.store') }}" x-show="!editId" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        <div>
            <label class="block text-[10px] tracking-widest uppercase text-text-muted mb-1.5">Full Name *</label>
            <input type="text" name="full_name" required class="w-full border border-sand/60 px-4 py-2.5 text-sm font-sans focus:outline-none focus:border-sage">
        </div>
        <div>
            <label class="block text-[10px] tracking-widest uppercase text-text-muted mb-1.5">Phone *</label>
            <input type="text" name="phone" required class="w-full border border-sand/60 px-4 py-2.5 text-sm font-sans focus:outline-none focus:border-sage">
        </div>
        <div class="md:col-span-2">
            <label class="block text-[10px] tracking-widest uppercase text-text-muted mb-1.5">Address Line 1 *</label>
            <input type="text" name="address_line_1" required class="w-full border border-sand/60 px-4 py-2.5 text-sm font-sans focus:outline-none focus:border-sage">
        </div>
        <div class="md:col-span-2">
            <label class="block text-[10px] tracking-widest uppercase text-text-muted mb-1.5">Address Line 2</label>
            <input type="text" name="address_line_2" class="w-full border border-sand/60 px-4 py-2.5 text-sm font-sans focus:outline-none focus:border-sage">
        </div>
        <div>
            <label class="block text-[10px] tracking-widest uppercase text-text-muted mb-1.5">City *</label>
            <input type="text" name="city" required class="w-full border border-sand/60 px-4 py-2.5 text-sm font-sans focus:outline-none focus:border-sage">
        </div>
        <div>
            <label class="block text-[10px] tracking-widest uppercase text-text-muted mb-1.5">State *</label>
            <input type="text" name="state" required class="w-full border border-sand/60 px-4 py-2.5 text-sm font-sans focus:outline-none focus:border-sage">
        </div>
        <div>
            <label class="block text-[10px] tracking-widest uppercase text-text-muted mb-1.5">Country *</label>
            <input type="text" name="country" value="Nigeria" required class="w-full border border-sand/60 px-4 py-2.5 text-sm font-sans focus:outline-none focus:border-sage">
        </div>
        <div>
            <label class="block text-[10px] tracking-widest uppercase text-text-muted mb-1.5">Postal Code</label>
            <input type="text" name="postal_code" class="w-full border border-sand/60 px-4 py-2.5 text-sm font-sans focus:outline-none focus:border-sage">
        </div>
        <div class="md:col-span-2 flex items-center gap-2">
            <input type="checkbox" name="is_default" id="is_default_add" value="1" class="accent-mahogany-600">
            <label for="is_default_add" class="text-sm font-sans text-text-muted">Set as default address</label>
        </div>
        <div class="md:col-span-2 flex gap-3 pt-2">
            <button type="submit" class="btn-primary text-xs py-2.5 px-6">Save Address</button>
            <button type="button" @click="showForm = false" class="btn-ghost text-xs py-2.5 px-4">Cancel</button>
        </div>
    </form>

    {{-- Edit forms (one per address, toggled by editId) --}}
    @foreach($addresses as $address)
    <form method="POST" action="{{ route('account.addresses.update', $address) }}" x-show="editId === {{ $address->id }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-[10px] tracking-widest uppercase text-text-muted mb-1.5">Full Name *</label>
            <input type="text" name="full_name" value="{{ $address->full_name }}" required class="w-full border border-sand/60 px-4 py-2.5 text-sm font-sans focus:outline-none focus:border-sage">
        </div>
        <div>
            <label class="block text-[10px] tracking-widest uppercase text-text-muted mb-1.5">Phone *</label>
            <input type="text" name="phone" value="{{ $address->phone }}" required class="w-full border border-sand/60 px-4 py-2.5 text-sm font-sans focus:outline-none focus:border-sage">
        </div>
        <div class="md:col-span-2">
            <label class="block text-[10px] tracking-widest uppercase text-text-muted mb-1.5">Address Line 1 *</label>
            <input type="text" name="address_line_1" value="{{ $address->address_line_1 }}" required class="w-full border border-sand/60 px-4 py-2.5 text-sm font-sans focus:outline-none focus:border-sage">
        </div>
        <div>
            <label class="block text-[10px] tracking-widest uppercase text-text-muted mb-1.5">City *</label>
            <input type="text" name="city" value="{{ $address->city }}" required class="w-full border border-sand/60 px-4 py-2.5 text-sm font-sans focus:outline-none focus:border-sage">
        </div>
        <div>
            <label class="block text-[10px] tracking-widest uppercase text-text-muted mb-1.5">State *</label>
            <input type="text" name="state" value="{{ $address->state }}" required class="w-full border border-sand/60 px-4 py-2.5 text-sm font-sans focus:outline-none focus:border-sage">
        </div>
        <div>
            <label class="block text-[10px] tracking-widest uppercase text-text-muted mb-1.5">Country *</label>
            <input type="text" name="country" value="{{ $address->country }}" required class="w-full border border-sand/60 px-4 py-2.5 text-sm font-sans focus:outline-none focus:border-sage">
        </div>
        <div class="md:col-span-2 flex gap-3 pt-2">
            <button type="submit" class="btn-primary text-xs py-2.5 px-6">Update</button>
            <button type="button" @click="showForm = false; editId = null" class="btn-ghost text-xs py-2.5 px-4">Cancel</button>
        </div>
    </form>
    @endforeach
</div>

{{-- Address Cards --}}
@forelse($addresses as $address)
<div class="bg-white shadow-luxury p-5 mb-4">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div class="flex-1">
            <div class="flex items-center gap-2 mb-1">
                <p class="font-sans font-semibold text-text-dark">{{ $address->full_name }}</p>
                @if($address->is_default)
                <span class="text-[9px] tracking-widest uppercase px-2 py-0.5 bg-sand/40 text-sage font-sans">Default</span>
                @endif
            </div>
            <p class="text-sm font-sans text-text-muted leading-relaxed">
                {{ $address->address_line_1 }}@if($address->address_line_2), {{ $address->address_line_2 }}@endif<br>
                {{ $address->city }}, {{ $address->state }}, {{ $address->country }}
            </p>
            <p class="text-xs font-sans text-text-muted mt-1">{{ $address->phone }}</p>
        </div>
        <div class="flex items-center gap-4">
            @if(!$address->is_default)
            <form method="POST" action="{{ route('account.addresses.default', $address) }}">
                @csrf @method('PATCH')
                <button type="submit" class="text-xs font-sans text-text-muted hover:text-sage transition-colors">Set default</button>
            </form>
            @endif
            <button @click="showForm = true; editId = {{ $address->id }}" class="text-xs font-sans text-text-muted hover:text-sage transition-colors">Edit</button>
            <form method="POST" action="{{ route('account.addresses.destroy', $address) }}" onsubmit="return confirm('Delete this address?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs font-sans text-mahogany hover:text-mahogany transition-colors">Delete</button>
            </form>
        </div>
    </div>
</div>
@empty
<div class="bg-white shadow-luxury p-10 text-center">
    <p class="font-display text-xl text-text-dark mb-3">No addresses saved</p>
    <p class="text-text-muted text-sm mb-6 font-sans">Add an address to speed up checkout.</p>
    <button @click="showForm = true" class="btn-primary">Add Address</button>
</div>
@endforelse

</div>
@endsection
