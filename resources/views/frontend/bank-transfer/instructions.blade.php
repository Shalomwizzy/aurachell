@extends('layouts.app')
@section('title', 'Complete Your Payment — Aurachell')
@section('content')

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    @if(session('success'))
    <div class="mb-6 px-4 py-3 text-sm font-sans" style="background:rgba(55,18,32,0.08);border:1px solid rgba(55,18,32,0.20);color:#371220;">
        {{ session('success') }}
    </div>
    @endif

    {{-- Header --}}
    <div class="text-center mb-10">
        <div class="w-16 h-16 mx-auto mb-5 flex items-center justify-center" style="background:rgba(55,18,32,0.08);border-radius:50%;">
            <svg class="w-8 h-8" style="color:#371220;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
        </div>
        <h1 class="font-display text-3xl text-text-dark mb-2">Complete Your Transfer</h1>
        <p class="text-text-muted font-sans text-sm">Order #{{ $order->order_number }} is reserved for you. Transfer the amount below to confirm it.</p>
    </div>

    {{-- Amount box --}}
    <div class="text-center mb-8 p-6 border" style="background:rgba(55,18,32,0.04);border-color:rgba(55,18,32,0.15);">
        <p class="text-xs tracking-widest uppercase text-text-muted font-sans mb-2">Amount to Transfer</p>
        <p class="font-display text-4xl" style="color:#371220;">₦{{ number_format($order->total, 2) }}</p>
        <p class="text-xs text-text-muted font-sans mt-2">Reference: <strong>{{ $bankTransfer->reference }}</strong></p>
        <p class="text-xs text-text-muted font-sans mt-1">Include this reference in your transfer narration</p>
    </div>

    {{-- Bank Details --}}
    <div class="mb-8 p-6 border" style="border-color:rgba(201,169,111,0.35);background:rgba(201,169,111,0.04);">
        <h2 class="font-display text-lg text-text-dark mb-5">Transfer To</h2>
        <div class="space-y-4">
            <div class="flex justify-between items-center border-b pb-4" style="border-color:rgba(55,18,32,0.10);">
                <span class="text-xs tracking-widest uppercase text-text-muted font-sans">Bank Name</span>
                <span class="text-sm font-semibold text-text-dark font-sans">{{ $bankDetails['bank_name'] ?: '—' }}</span>
            </div>
            <div class="flex justify-between items-center border-b pb-4" style="border-color:rgba(55,18,32,0.10);">
                <span class="text-xs tracking-widest uppercase text-text-muted font-sans">Account Name</span>
                <span class="text-sm font-semibold text-text-dark font-sans">{{ $bankDetails['account_name'] ?: '—' }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xs tracking-widest uppercase text-text-muted font-sans">Account Number</span>
                <div class="flex items-center gap-2">
                    <span class="text-xl font-display font-bold tracking-widest" style="color:#371220;" id="acct-num">{{ $bankDetails['account_number'] ?: '—' }}</span>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $bankDetails['account_number'] }}').then(()=>this.textContent='Copied!')"
                            class="text-[10px] tracking-wider uppercase font-sans transition-colors px-2 py-1"
                            style="background:rgba(55,18,32,0.08);color:rgba(55,18,32,0.60);border:1px solid rgba(55,18,32,0.15);">Copy</button>
                </div>
            </div>
        </div>
        @if(!empty($bankDetails['instructions']))
        <p class="mt-5 text-xs font-sans leading-relaxed text-text-muted">{{ $bankDetails['instructions'] }}</p>
        @endif
    </div>

    @if($bankTransfer->proof_path)
    {{-- Proof already uploaded --}}
    <div class="p-6 text-center border" style="background:rgba(55,18,32,0.04);border-color:rgba(55,18,32,0.15);">
        <svg class="w-10 h-10 mx-auto mb-3" style="color:#371220;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="font-display text-lg text-text-dark mb-1">Proof Submitted</p>
        <p class="text-sm text-text-muted font-sans">Your payment proof has been received. We'll confirm your order within 24 hours and send you an email.</p>
    </div>

    @else
    {{-- Proof upload form --}}
    <div x-data="{ uploading: false, fileChosen: false, fileName: '' }">

        <div class="mb-6 p-4 text-sm font-sans" style="background:rgba(201,169,111,0.08);border:1px solid rgba(201,169,111,0.25);color:rgba(55,18,32,0.75);">
            <strong>Steps:</strong> Make the transfer to the account above → take a screenshot or save the receipt → click "I Have Made Payment" below and upload it.
        </div>

        <form action="{{ route('bank-transfer.proof', $order->order_number) }}" method="POST" enctype="multipart/form-data"
              @submit="uploading = true">
            @csrf

            @if($errors->any())
            <div class="mb-4 px-4 py-3 text-sm font-sans" style="background:rgba(55,18,32,0.08);border:1px solid rgba(55,18,32,0.20);color:#371220;">
                {{ $errors->first() }}
            </div>
            @endif

            {{-- File upload --}}
            <div class="mb-5">
                <label class="block text-xs tracking-widest uppercase text-text-muted font-sans mb-2">Payment Receipt / Screenshot *</label>
                <div class="relative border-2 border-dashed transition-colors p-8 text-center"
                     style="border-color:rgba(55,18,32,0.20);"
                     :class="fileChosen ? 'border-mahogany/60' : ''">
                    <input type="file" name="proof" accept=".jpg,.jpeg,.png,.pdf,.webp" required
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                           @change="fileChosen = true; fileName = $event.target.files[0]?.name ?? ''">
                    <template x-if="!fileChosen">
                        <div>
                            <svg class="w-8 h-8 mx-auto mb-2 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="text-sm text-text-muted font-sans">Click or drag your receipt here</p>
                            <p class="text-xs text-text-muted font-sans mt-1">JPG, PNG, PDF or WEBP — max 5MB</p>
                        </div>
                    </template>
                    <template x-if="fileChosen">
                        <div>
                            <svg class="w-6 h-6 mx-auto mb-1" style="color:#371220;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4"/>
                            </svg>
                            <p class="text-sm text-text-dark font-sans" x-text="fileName"></p>
                            <p class="text-xs text-text-muted font-sans mt-1">Click to change file</p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Optional note --}}
            <div class="mb-6">
                <label class="block text-xs tracking-widest uppercase text-text-muted font-sans mb-2">Additional Note (optional)</label>
                <textarea name="customer_note" rows="2" placeholder="e.g. Transfer sent from GTBank on 29 May 2026"
                          class="input-luxury w-full resize-none text-sm"></textarea>
            </div>

            <button type="submit" :disabled="uploading || !fileChosen"
                    class="w-full btn-primary py-4 flex items-center justify-center gap-3 transition-opacity"
                    :class="(uploading || !fileChosen) ? 'opacity-50 cursor-not-allowed' : ''">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                <span x-text="uploading ? 'Submitting...' : 'I Have Made Payment'"></span>
            </button>

            <p class="text-center text-xs text-text-muted font-sans mt-4">
                Our team will verify your transfer and confirm your order within 24 hours.
                You'll receive an email notification once confirmed.
            </p>
        </form>
    </div>
    @endif

    {{-- Back link --}}
    <div class="mt-10 text-center">
        <a href="{{ route('shop') }}" class="text-xs text-text-muted font-sans underline underline-offset-4 hover:text-text-dark transition-colors">Continue browsing</a>
    </div>

</div>
@endsection
