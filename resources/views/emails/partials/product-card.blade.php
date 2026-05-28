{{--
    Reusable email product card.
    Props: $product (Product model), $qty (optional int), $price (optional), $showBtn (optional bool)
--}}
@php
    $showBtn   = $showBtn   ?? false;
    $qty       = $qty       ?? null;
    $cardPrice = $price     ?? $product->price ?? null;
@endphp
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%"
       style="border:1px solid rgba(55,18,32,0.10);background:#FAF5ED;margin-bottom:12px;">
    <tr>
        @if($product->primary_image_url ?? false)
        <td width="90" style="vertical-align:top;padding:0;">
            <img src="{{ $product->primary_image_url }}"
                 alt="{{ $product->name }}"
                 width="90" height="108"
                 style="display:block;width:90px;height:108px;object-fit:cover;">
        </td>
        @endif
        <td style="padding:16px 18px;vertical-align:top;">
            @if($product->category?->name ?? false)
            <p style="font-family:Arial,sans-serif;font-size:9px;letter-spacing:0.22em;text-transform:uppercase;color:rgba(55,18,32,0.40);margin:0 0 5px;">
                {{ $product->category->name }}
            </p>
            @endif
            <p style="font-family:Georgia,serif;font-size:15px;color:#371220;margin:0 0 6px;line-height:1.3;">
                {{ $product->name }}
            </p>
            @if($qty)
            <p style="font-family:Arial,sans-serif;font-size:11px;color:rgba(55,18,32,0.50);margin:0 0 6px;">
                Qty: {{ $qty }}
            </p>
            @endif
            @if($cardPrice)
            <p style="font-family:Arial,sans-serif;font-size:14px;font-weight:700;color:#371220;margin:0 0 12px;letter-spacing:0.02em;">
                ₦{{ number_format($cardPrice) }}
            </p>
            @endif
            @if($showBtn && ($product->slug ?? false))
            <a href="{{ route('product.show', $product->slug) }}"
               style="display:inline-block;padding:9px 20px;background:#371220;color:#FAF5ED;text-decoration:none;font-family:Arial,sans-serif;font-size:9px;font-weight:700;letter-spacing:0.22em;text-transform:uppercase;">
                View Product
            </a>
            @endif
        </td>
    </tr>
</table>
