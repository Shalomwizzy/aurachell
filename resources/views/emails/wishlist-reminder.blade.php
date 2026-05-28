@extends('emails.layouts.base')
@section('subject', 'Your wishlist is still waiting for you')
@section('preheader', 'The pieces you saved are still here — just as beautiful as the day you found them.')

@section('hero')
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:#1E0C14;">
    <tr>
        <td style="padding:44px 48px;text-align:center;">
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.35em;text-transform:uppercase;color:rgba(201,169,111,0.55);margin:0 0 14px;">
                Your Saved Pieces
            </p>
            <h1 style="font-family:Georgia,serif;font-size:30px;color:#FAF5ED;font-weight:normal;margin:0 0 10px;">
                They're still here.<br>Waiting for you.
            </h1>
            <p style="font-family:Georgia,serif;font-size:14px;font-style:italic;color:rgba(250,245,237,0.48);margin:0;">
                Your taste doesn't expire. Neither does our dedication to it.
            </p>
        </td>
    </tr>
</table>
@endsection

@section('content')

<p>
    {{ explode(' ', $user->name)[0] }}, a little while ago you set aside something beautiful — and it's still on your list. We wanted to remind you, because pieces chosen with this much intention deserve a second look.
</p>

<hr class="gold-rule">

<h3 style="margin-bottom:20px;">What you saved</h3>

@foreach($items->take(4) as $wish)
@php $product = $wish->product; @endphp
@if($product)
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="border:1px solid rgba(201,169,111,0.18);background:#FAF5ED;margin-bottom:12px;">
    <tr>
        @if($product->primary_image_url ?? false)
        <td width="88" style="vertical-align:top;padding:0;">
            <img src="{{ $product->primary_image_url }}"
                 alt="{{ $product->name }}"
                 width="88" height="106"
                 style="display:block;width:88px;height:106px;object-fit:cover;">
        </td>
        @endif
        <td style="padding:16px 18px;vertical-align:middle;">
            @if($product->category)
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.22em;text-transform:uppercase;color:rgba(201,169,111,0.75);margin:0 0 5px;">
                {{ $product->category->name }}
            </p>
            @endif
            <p style="font-family:Georgia,serif;font-size:15px;color:#1E0C14;margin:0 0 6px;line-height:1.3;">
                {{ $product->name }}
            </p>
            @if($product->short_description)
            <p style="font-family:Arial,sans-serif;font-size:11px;color:rgba(30,12,20,0.50);margin:0 0 10px;line-height:1.5;">
                {{ Str::limit($product->short_description, 80) }}
            </p>
            @endif
            <p style="font-family:Georgia,serif;font-size:16px;color:#C9A96F;margin:0;font-weight:600;">
                ₦{{ number_format($product->price) }}
            </p>
            @if(isset($product->stock_quantity) && $product->stock_quantity <= 5 && $product->stock_quantity > 0)
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.15em;text-transform:uppercase;color:#b91c1c;margin:6px 0 0;font-weight:700;">
                Only {{ $product->stock_quantity }} left
            </p>
            @endif
        </td>
        <td width="110" style="padding:16px;vertical-align:middle;text-align:center;">
            @if(!isset($product->stock_quantity) || $product->stock_quantity > 0)
            <a href="{{ route('product.show', $product->slug) }}"
               style="display:inline-block;padding:9px 16px;background:#371220;color:#FAF5ED;font-family:Arial,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;text-decoration:none;">
                Shop Now
            </a>
            @else
            <span style="display:inline-block;padding:4px 10px;background:rgba(220,38,38,0.08);color:#b91c1c;font-family:Arial,sans-serif;font-size:9px;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;">
                Sold Out
            </span>
            @endif
        </td>
    </tr>
</table>
@endif
@endforeach

{{-- CTA --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td style="text-align:center;padding:28px 0 20px;">
            <a href="{{ route('account.wishlist') }}" class="btn">View My Full Wishlist</a>
        </td>
    </tr>
</table>

<hr class="gold-rule">

{{-- Brand philosophy --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:rgba(55,18,32,0.04);border-left:2px solid #C9A96F;padding:22px 26px;margin:0 0 28px;">
    <tr>
        <td>
            <p style="font-family:Georgia,serif;font-size:15px;font-style:italic;color:#371220;margin:0 0 10px;line-height:1.6;">
                At Aurachell, we believe your home should smell like a decision — intentional, warm, unmistakably yours.
            </p>
            <p style="font-family:Arial,sans-serif;font-size:11px;letter-spacing:0.18em;text-transform:uppercase;color:rgba(30,12,20,0.40);margin:0;">
                Handcrafted in Lagos
            </p>
        </td>
    </tr>
</table>

<p style="font-family:Georgia,serif;font-size:15px;font-style:italic;color:rgba(55,18,32,0.48);text-align:center;margin:0;">
    "Every fragrance is a memory you haven't made yet."
</p>

@endsection
