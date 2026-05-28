@extends('emails.layouts.base')
@section('subject', $product->name . ' is back — your wishlist item is available again')
@section('preheader', 'You saved it. Now it\'s back. Don\'t miss it this time.')

@section('hero')
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:#1E0C14;">
    <tr>
        <td style="padding:44px 48px;text-align:center;">
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.35em;text-transform:uppercase;color:rgba(201,169,111,0.55);margin:0 0 14px;">
                Back in Stock
            </p>
            <h1 style="font-family:Georgia,serif;font-size:30px;color:#FAF5ED;font-weight:normal;margin:0 0 10px;line-height:1.25;">
                {{ $product->name }}<br>is available again.
            </h1>
            <p style="font-family:Georgia,serif;font-size:14px;font-style:italic;color:rgba(250,245,237,0.48);margin:0;">
                You saved it for a reason. Now's your chance.
            </p>
        </td>
    </tr>
</table>
@endsection

@section('content')

<p>
    {{ explode(' ', $user->name)[0] }}, good news — the piece you wishlisted is back. Stock is limited, and given that it sold out before, we expect it won't be available for long. We wanted you to be first to know.
</p>

{{-- Product highlight --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="border:1px solid rgba(201,169,111,0.20);background:#FAF5ED;margin:24px 0;overflow:hidden;">
    <tr>
        @if($product->primary_image_url ?? false)
        <td style="padding:0;text-align:center;">
            <img src="{{ $product->primary_image_url }}"
                 alt="{{ $product->name }}"
                 width="560"
                 style="display:block;width:100%;max-height:280px;object-fit:cover;">
        </td>
        @endif
    </tr>
    <tr>
        <td style="padding:24px 28px;">
            @if($product->category)
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.25em;text-transform:uppercase;color:rgba(201,169,111,0.75);margin:0 0 8px;">
                {{ $product->category->name }}
            </p>
            @endif
            <h2 style="font-family:Georgia,serif;font-size:20px;color:#1E0C14;font-weight:normal;margin:0 0 10px;line-height:1.3;">
                {{ $product->name }}
            </h2>
            @if($product->short_description)
            <p style="font-family:Arial,sans-serif;font-size:13px;color:rgba(30,12,20,0.55);margin:0 0 18px;line-height:1.7;">
                {{ $product->short_description }}
            </p>
            @endif
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td style="vertical-align:middle;">
                        <p style="font-family:Georgia,serif;font-size:20px;color:#C9A96F;margin:0;font-weight:600;">
                            ₦{{ number_format($product->price) }}
                        </p>
                        @if(isset($product->stock_quantity) && $product->stock_quantity <= 10)
                        <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.15em;text-transform:uppercase;color:#371220;margin:5px 0 0;font-weight:700;">
                            Only {{ $product->stock_quantity }} remaining
                        </p>
                        @endif
                    </td>
                    <td style="text-align:right;vertical-align:middle;">
                        <a href="{{ route('product.show', $product->slug) }}"
                           style="display:inline-block;padding:12px 26px;background:#371220;color:#FAF5ED;font-family:Arial,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.20em;text-transform:uppercase;text-decoration:none;">
                            Shop Now
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{-- Urgency note --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:#371220;padding:20px 28px;margin:0 0 28px;">
    <tr>
        <td>
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.22em;text-transform:uppercase;color:rgba(201,169,111,0.65);margin:0 0 6px;">
                A gentle note
            </p>
            <p style="font-family:Georgia,serif;font-size:14px;color:rgba(250,245,237,0.80);margin:0;line-height:1.6;">
                We restocked a limited quantity. These pieces sold out once — and we can't guarantee how long they'll last.
            </p>
        </td>
    </tr>
</table>

{{-- Browse more --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td style="text-align:center;padding:8px 0 32px;">
            <a href="{{ route('account.wishlist') }}" class="btn-ghost" style="font-size:10px;letter-spacing:0.22em;">View My Full Wishlist</a>
        </td>
    </tr>
</table>

<p style="font-family:Georgia,serif;font-size:15px;font-style:italic;color:rgba(55,18,32,0.48);text-align:center;margin:0;">
    "Every fragrance is a memory you haven't made yet."
</p>

@endsection
