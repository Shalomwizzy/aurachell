<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice — {{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #2C2C2C; background: #fff; font-size: 13px; line-height: 1.6; }
        .container { max-width: 800px; margin: 40px auto; padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 48px; padding-bottom: 24px; border-bottom: 1px solid #E5E5E5; }
        .brand-name { font-family: Georgia, serif; font-size: 24px; letter-spacing: 0.2em; text-transform: uppercase; color: #1A0D16; }
        .brand-tagline { font-size: 11px; color: #999; letter-spacing: 0.15em; text-transform: uppercase; margin-top: 4px; }
        .invoice-title { text-align: right; }
        .invoice-title h1 { font-family: Georgia, serif; font-size: 28px; color: #1A0D16; }
        .invoice-title p { font-size: 12px; color: #999; margin-top: 4px; }
        .meta-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px; margin-bottom: 40px; }
        .meta-block label { font-size: 10px; letter-spacing: 0.2em; text-transform: uppercase; color: #999; display: block; margin-bottom: 6px; }
        .meta-block p { font-size: 13px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        thead tr { border-bottom: 2px solid #1A0D16; }
        th { padding: 10px 12px; text-align: left; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: #1A0D16; font-weight: 600; }
        th:last-child, td:last-child { text-align: right; }
        tbody tr { border-bottom: 1px solid #F0F0F0; }
        td { padding: 12px; font-size: 13px; color: #444; vertical-align: top; }
        .totals { margin-left: auto; width: 260px; margin-bottom: 40px; }
        .totals-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; color: #666; }
        .totals-row.total { border-top: 2px solid #1A0D16; padding-top: 12px; margin-top: 6px; font-family: Georgia, serif; font-size: 18px; color: #1A0D16; font-weight: bold; }
        .footer { border-top: 1px solid #E5E5E5; padding-top: 24px; text-align: center; font-size: 11px; color: #999; }
        @media print { body { font-size: 12px; } .container { padding: 20px; } }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <div class="brand-name">Aurachell</div>
            <div class="brand-tagline">Crafted for Calm. Designed for Home.</div>
            <div style="margin-top:12px;font-size:12px;color:#666;">
                hello@aurachell.com<br>
                Lagos, Nigeria
            </div>
        </div>
        <div class="invoice-title">
            <h1>Invoice</h1>
            <p>#{{ $order->order_number }}</p>
            <p style="margin-top:8px;">{{ $order->created_at->format('d F Y') }}</p>
        </div>
    </div>

    <div class="meta-grid">
        <div class="meta-block">
            <label>Billed To</label>
            <p>{{ $order->user?->name ?? 'Guest' }}</p>
            <p style="color:#999;font-size:12px;">{{ $order->user?->email }}</p>
        </div>
        <div class="meta-block">
            @php $addr = $order->shipping_address; @endphp
            <label>Ship To</label>
            <p>{{ $addr['address_line_1'] ?? '' }}</p>
            @if(!empty($addr['address_line_2']))<p style="color:#999;font-size:12px;">{{ $addr['address_line_2'] }}</p>@endif
            <p style="color:#999;font-size:12px;">{{ ($addr['city'] ?? '') . ', ' . ($addr['state'] ?? '') }}</p>
        </div>
        <div class="meta-block">
            <label>Payment</label>
            <p>{{ ucfirst($order->payment_status) }}</p>
            <p style="color:#999;font-size:12px;">{{ ucfirst($order->payment_method ?? 'Paystack') }}</p>
            @if($order->tracking_code)
            <div style="margin-top:8px;">
                <label>Tracking</label>
                <p>{{ $order->tracking_code }}</p>
            </div>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    <strong>{{ $item->product_name }}</strong>
                    @if($item->variant_name)
                    <br><span style="color:#999;font-size:11px;">{{ $item->variant_name }}</span>
                    @endif
                </td>
                <td>{{ $item->quantity }}</td>
                <td>₦{{ number_format($item->unit_price, 0) }}</td>
                <td>₦{{ number_format($item->total_price, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="totals-row"><span>Subtotal</span><span>₦{{ number_format($order->subtotal, 0) }}</span></div>
        @if($order->discount > 0)
        <div class="totals-row" style="color:#1A0D16;"><span>Discount</span><span>−₦{{ number_format($order->discount, 0) }}</span></div>
        @endif
        <div class="totals-row"><span>Shipping</span><span>{{ $order->shipping_fee > 0 ? '₦'.number_format($order->shipping_fee, 0) : 'Free' }}</span></div>
        <div class="totals-row total"><span>Total</span><span>₦{{ number_format($order->total, 0) }}</span></div>
    </div>

    <div class="footer">
        <p>Thank you for your order! For questions, contact us at hello@aurachell.com</p>
        <p style="margin-top:4px;">Aurachell — Crafted for Calm. Designed for Home.</p>
    </div>
</div>
</body>
</html>
