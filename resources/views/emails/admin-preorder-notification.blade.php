@extends('emails.layouts.inline')
@section('subject', 'New pre-order received')
@section('preheader', 'A customer has pre-ordered an out-of-stock product.')

@section('content')
<div style="font-family:Arial,Helvetica,sans-serif;font-size:10px;letter-spacing:3px;text-transform:uppercase;color:#A9885A;margin-bottom:14px;">Admin Notification</div>
<h1 style="font-family:Georgia,'Times New Roman',serif;font-size:26px;font-weight:normal;color:#371220;margin:0 0 14px;line-height:1.2;">New pre-order received</h1>
<p style="font-family:Georgia,'Times New Roman',serif;font-size:15px;color:#5c4a45;line-height:1.7;margin:0 0 26px;">A customer just pre-ordered a product that is currently out of stock. Restock it and reach out to complete the sale.</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F1E4D3;border-left:3px solid #C9A96F;margin:0 0 28px;"><tr>
<td style="padding:22px 24px;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-family:Arial,Helvetica,sans-serif;">
        <tr>
            <td style="padding:0 0 4px;font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Product</td>
            <td align="right" style="padding:0 0 4px;font-size:15px;font-weight:bold;color:#371220;">{{ $preorder->product->name }}</td>
        </tr>
        <tr>
            <td colspan="2" align="right" style="padding:0 0 10px;font-size:12px;color:#8a7266;">SKU: {{ $preorder->product->sku ?? '—' }} · ₦{{ number_format($preorder->product->price, 0) }} · Qty: {{ $preorder->quantity }}</td>
        </tr>
        <tr>
            <td style="padding:12px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Customer</td>
            <td align="right" style="padding:12px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:14px;color:#371220;">{{ $preorder->customer_name }}</td>
        </tr>
        <tr><td colspan="2" align="right" style="padding:0 0 2px;font-size:13px;color:#5c4a45;">{{ $preorder->customer_email }}</td></tr>
        @if($preorder->customer_phone)
        <tr><td colspan="2" align="right" style="padding:0 0 4px;font-size:13px;color:#5c4a45;">☎ {{ $preorder->customer_phone }}</td></tr>
        @endif
    </table>
</td>
</tr></table>

@if($preorder->note)
<div style="font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;margin:0 0 6px;">Customer Note</div>
<p style="font-family:Georgia,'Times New Roman',serif;font-size:14px;color:#5c4a45;line-height:1.6;margin:0 0 28px;">{{ $preorder->note }}</p>
@endif

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:6px 0 0;"><tr><td align="center">
    <a href="{{ route('admin.preorders.index') }}" style="display:inline-block;background-color:#371220;color:#FAF5ED;text-decoration:none;padding:15px 42px;font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:bold;letter-spacing:3px;text-transform:uppercase;">View Pre-orders</a>
</td></tr></table>
@endsection
