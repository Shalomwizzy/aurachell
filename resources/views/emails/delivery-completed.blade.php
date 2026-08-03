@extends('emails.layouts.inline')
@section('subject', 'Your Aurachell has arrived — ' . $order->order_number)
@section('preheader', 'Your order is delivered. Now the ritual begins.')

@section('hero')
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#371220;">
<tr><td align="center" style="padding:40px 40px 44px;">
    <div style="font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:4px;text-transform:uppercase;color:#B79B78;margin:0 0 14px;">Delivered</div>
    <div style="font-family:Georgia,'Times New Roman',serif;font-size:30px;color:#FAF5ED;line-height:1.2;margin:0 0 10px;">Your Aurachell has arrived.</div>
    <div style="font-family:Georgia,'Times New Roman',serif;font-size:14px;font-style:italic;color:rgba(250,245,237,0.55);">Now the ritual begins.</div>
</td></tr>
</table>
@endsection

@section('content')
<p style="font-family:Georgia,'Times New Roman',serif;font-size:15px;color:#5c4a45;line-height:1.75;margin:0 0 22px;">{{ explode(' ', $user->name)[0] }}, your order has been delivered. We hope the unboxing felt as special as the scent inside — every piece from Aurachell was chosen, crafted, and packed with care, and it's finally yours.</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F1E4D3;border-left:3px solid #C9A96F;margin:0 0 28px;"><tr>
<td align="center" style="padding:22px 24px;">
    <div style="font-family:Arial,Helvetica,sans-serif;font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;margin:0 0 6px;">Order Reference</div>
    <div style="font-family:Arial,Helvetica,sans-serif;font-size:16px;font-weight:bold;color:#371220;letter-spacing:1px;">{{ $order->order_number }}</div>
</td>
</tr></table>

<div style="font-family:Georgia,'Times New Roman',serif;font-size:17px;color:#371220;margin:0 0 14px;">Getting the most from your Aurachell</div>
@foreach([
    ['Place with intention', 'Position your diffuser where air flows naturally — near a doorway, beside a window, or at nose level on a shelf.'],
    ['Start gently', 'Begin with fewer reeds inserted; add more as you dial in your preferred intensity.'],
    ['Flip weekly', 'Turn your reeds once a week to refresh the fragrance throw.'],
    ['Keep away from heat', 'A cool, shaded spot away from direct sun extends your diffuser\'s life.'],
] as [$tip, $desc])
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 14px;"><tr>
    <td width="20" style="vertical-align:top;padding-top:2px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#C9A96F;">✦</td>
    <td>
        <div style="font-family:Georgia,'Times New Roman',serif;font-size:14px;color:#371220;font-weight:bold;margin:0 0 3px;">{{ $tip }}</div>
        <div style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#8a7266;line-height:1.6;">{{ $desc }}</div>
    </td>
</tr></table>
@endforeach

<div style="height:1px;background-color:rgba(201,169,111,0.45);margin:28px 0;"></div>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F4E9DA;border-left:2px solid #371220;margin:0 0 26px;"><tr>
<td style="padding:22px 26px;">
    <div style="font-family:Georgia,'Times New Roman',serif;font-size:17px;color:#371220;margin:0 0 10px;">Your voice helps others find their scent.</div>
    <p style="font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#8a7266;line-height:1.6;margin:0 0 18px;">If you're enjoying your Aurachell experience, a short review means the world to us — and to the customers still searching for their perfect fragrance.</p>
    <a href="{{ route('account.reviews') }}" style="display:inline-block;background-color:transparent;color:#371220;text-decoration:none;padding:12px 34px;border:1px solid rgba(55,18,32,0.35);font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;">Leave a Review</a>
</td>
</tr></table>

<p style="font-family:Georgia,'Times New Roman',serif;font-size:15px;font-style:italic;color:#a08a7f;text-align:center;margin:0;">"Every fragrance is a memory you haven't made yet."</p>
@endsection
