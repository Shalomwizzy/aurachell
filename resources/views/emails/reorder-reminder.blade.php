@extends('emails.layouts.base')
@section('subject', 'Time to refresh your Aurachell, ' . explode(' ', $user->name)[0])
@section('preheader', 'Your favourites are ready to be restocked — and your space will thank you.')

@section('hero')
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="background:#1E0C14;">
    <tr>
        <td style="padding:44px 48px;text-align:center;">
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.35em;text-transform:uppercase;color:rgba(201,169,111,0.55);margin:0 0 14px;">
                Time to Restock
            </p>
            <h1 style="font-family:Georgia,serif;font-size:30px;color:#FAF5ED;font-weight:normal;margin:0 0 10px;line-height:1.25;">
                Your home deserves<br>a refresh.
            </h1>
            <p style="font-family:Georgia,serif;font-size:14px;font-style:italic;color:rgba(250,245,237,0.48);margin:0;">
                The scent you loved is still here — and still just as good.
            </p>
        </td>
    </tr>
</table>
@endsection

@section('content')

<p>
    {{ explode(' ', $user->name)[0] }}, it's been a while since your last order. If your Aurachell diffuser is running low — or if the scent has started to fade — it might be time to bring it back to life.
</p>

<p>
    Your space misses it. We thought you should know.
</p>

<hr class="gold-rule">

{{-- Previous order items to reorder --}}
@if(isset($order) && $order->items->count())
<h3 style="margin-bottom:16px;">From your last order</h3>

@foreach($order->items->take(3) as $item)
@if($item->product ?? false)
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="border:1px solid rgba(201,169,111,0.18);background:#FAF5ED;margin-bottom:12px;">
    <tr>
        @if($item->product->primary_image_url ?? false)
        <td width="80" style="vertical-align:top;padding:0;">
            <img src="{{ $item->product->primary_image_url }}"
                 alt="{{ $item->product->name }}"
                 width="80" height="96"
                 style="display:block;width:80px;height:96px;object-fit:cover;">
        </td>
        @endif
        <td style="padding:14px 18px;vertical-align:middle;">
            <p style="font-family:Georgia,serif;font-size:14px;color:#1E0C14;margin:0 0 4px;line-height:1.3;">
                {{ $item->product->name }}
            </p>
            @if($item->scent_note)
            <p style="font-family:Arial,sans-serif;font-size:11px;color:rgba(30,12,20,0.48);margin:0 0 8px;">
                Scent: {{ $item->scent_note }}
            </p>
            @endif
            <p style="font-family:Georgia,serif;font-size:15px;color:#C9A96F;margin:0;font-weight:600;">
                ₦{{ number_format($item->product->price) }}
            </p>
        </td>
        <td width="110" style="padding:14px 16px;vertical-align:middle;text-align:right;">
            @if(!isset($item->product->stock_quantity) || $item->product->stock_quantity > 0)
            <a href="{{ route('product.show', $item->product->slug) }}"
               style="display:inline-block;padding:9px 16px;background:#371220;color:#FAF5ED;font-family:Arial,sans-serif;font-size:10px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;text-decoration:none;">
                Order Again
            </a>
            @else
            <span style="display:inline-block;padding:4px 10px;background:rgba(55,18,32,0.10);color:#371220;font-family:Arial,sans-serif;font-size:9px;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;">
                Sold Out
            </span>
            @endif
        </td>
    </tr>
</table>
@else
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="border-bottom:1px solid rgba(55,18,32,0.08);padding:12px 0;margin-bottom:0;">
    <tr>
        <td>
            <p style="font-family:Georgia,serif;font-size:14px;color:#1E0C14;margin:0 0 2px;">{{ $item->product_name }}</p>
            @if($item->scent_note)
            <p style="font-family:Arial,sans-serif;font-size:11px;color:rgba(30,12,20,0.48);margin:0;">Scent: {{ $item->scent_note }}</p>
            @endif
        </td>
    </tr>
</table>
@endif
@endforeach
@endif

{{-- CTA --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td style="text-align:center;padding:28px 0 16px;">
            <a href="{{ route('shop') }}" class="btn">Restock My Favourites</a>
        </td>
    </tr>
    <tr>
        <td style="text-align:center;">
            <a href="{{ route('account.orders') }}" class="btn-ghost" style="font-size:10px;letter-spacing:0.22em;">View My Order History</a>
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
                A well-scented home is not a luxury — it is a practice. One worth returning to.
            </p>
            <p style="font-family:Arial,sans-serif;font-size:11px;letter-spacing:0.18em;text-transform:uppercase;color:rgba(30,12,20,0.40);margin:0;">
                Aurachell &nbsp;·&nbsp; Lagos
            </p>
        </td>
    </tr>
</table>

<p style="font-family:Georgia,serif;font-size:15px;font-style:italic;color:rgba(55,18,32,0.48);text-align:center;margin:0;">
    "Every fragrance is a memory you haven't made yet."
</p>

@endsection
