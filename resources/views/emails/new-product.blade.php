@extends('emails.layouts.base')
@section('subject', 'New Arrival — ' . $product->name)
@section('preheader', 'Something new has arrived in the Aurachell collection. You\'ll want to see this.')

@section('hero')
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:#371220;">
    <tr>
        <td style="padding:44px 48px;text-align:center;">
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.35em;text-transform:uppercase;color:rgba(201,169,111,0.55);margin:0 0 14px;">
                New Arrival
            </p>
            <h1 style="font-family:Georgia,serif;font-size:30px;color:#FAF5ED;font-weight:normal;margin:0 0 10px;line-height:1.25;">
                {{ $product->name }}
            </h1>
            <p style="font-family:Georgia,serif;font-size:14px;font-style:italic;color:rgba(250,245,237,0.48);margin:0;">
                Something new has joined the collection.
            </p>
        </td>
    </tr>
</table>
@endsection

@section('content')

<p>
    {{ explode(' ', $user->name)[0] }}, a new piece has arrived in the Aurachell collection — and we wanted you to be among the first to discover it. This is not just a product. It's a statement for your space.
</p>

{{-- Product feature --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="border:1px solid rgba(55,18,32,0.20);background:#FAF5ED;margin:24px 0;overflow:hidden;">
    <tr>
        @if($product->primary_image_url ?? false)
        <td style="padding:0;text-align:center;">
            <img src="{{ $product->primary_image_url }}"
                 alt="{{ $product->name }}"
                 width="560"
                 style="display:block;width:100%;max-height:320px;object-fit:cover;">
        </td>
        @endif
    </tr>
    <tr>
        <td style="padding:28px 32px;">
            @if($product->category)
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.28em;text-transform:uppercase;color:rgba(55,18,32,0.75);margin:0 0 10px;">
                {{ $product->category->name }}
            </p>
            @endif
            <h2 style="font-family:Georgia,serif;font-size:22px;color:#371220;font-weight:normal;margin:0 0 12px;line-height:1.3;">
                {{ $product->name }}
            </h2>
            @if($product->short_description)
            <p style="font-family:Arial,sans-serif;font-size:13px;color:rgba(55,18,32,0.58);margin:0 0 20px;line-height:1.7;">
                {{ $product->short_description }}
            </p>
            @endif
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td style="vertical-align:middle;">
                        <p style="font-family:Georgia,serif;font-size:22px;color:#371220;margin:0;font-weight:600;">
                            ₦{{ number_format($product->price) }}
                        </p>
                        @if(isset($product->compare_at_price) && $product->compare_at_price > $product->price)
                        <p style="font-family:Arial,sans-serif;font-size:12px;color:rgba(55,18,32,0.40);text-decoration:line-through;margin:3px 0 0;">
                            ₦{{ number_format($product->compare_at_price) }}
                        </p>
                        @endif
                    </td>
                    <td style="text-align:right;vertical-align:middle;">
                        <a href="{{ route('product.show', $product->slug) }}"
                           style="display:inline-block;padding:12px 28px;background:#371220;color:#FAF5ED;font-family:Arial,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.20em;text-transform:uppercase;text-decoration:none;">
                            Shop Now
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{-- Scent notes --}}
@if($product->scent_notes ?? false)
<hr class="gold-rule">
<h3 style="margin-bottom:16px;">The scent profile</h3>
<p style="font-family:Georgia,serif;font-size:14px;color:rgba(55,18,32,0.65);line-height:1.7;margin:0 0 24px;">
    {{ $product->scent_notes }}
</p>
@endif

{{-- Why it's special --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:rgba(55,18,32,0.04);border-left:2px solid #371220;padding:22px 26px;margin:0 0 28px;">
    <tr>
        <td>
            <p style="font-family:Georgia,serif;font-size:15px;font-style:italic;color:#371220;margin:0 0 10px;line-height:1.6;">
                Every Aurachell piece is handcrafted in Lagos using natural ingredients — no shortcuts, no compromises.
            </p>
            <p style="font-family:Arial,sans-serif;font-size:11px;letter-spacing:0.18em;text-transform:uppercase;color:rgba(55,18,32,0.40);margin:0;">
                Limited availability &nbsp;·&nbsp; Natural ingredients &nbsp;·&nbsp; Made in Lagos
            </p>
        </td>
    </tr>
</table>

{{-- Browse collection CTA --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td style="text-align:center;padding:8px 0 32px;">
            <a href="{{ route('shop') }}" class="btn-ghost" style="font-size:10px;letter-spacing:0.22em;">Explore the Full Collection</a>
        </td>
    </tr>
</table>

<p style="font-family:Georgia,serif;font-size:15px;font-style:italic;color:rgba(55,18,32,0.48);text-align:center;margin:0;">
    "Every fragrance is a memory you haven't made yet."
</p>

@endsection
