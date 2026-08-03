@extends('emails.layouts.inline')
@section('subject', ($isUpdate ? 'Updated' : 'New') . ' Review Submitted')
@section('preheader', $review->rating . '-star review on ' . ($review->product->name ?? 'a product') . ' — awaiting approval')

@section('content')
<div style="font-family:Arial,Helvetica,sans-serif;font-size:10px;letter-spacing:3px;text-transform:uppercase;color:#A9885A;margin-bottom:14px;">Admin Notification</div>
<h1 style="font-family:Georgia,'Times New Roman',serif;font-size:26px;font-weight:normal;color:#371220;margin:0 0 14px;line-height:1.2;">{{ $isUpdate ? 'Review Updated' : 'New Review Submitted' }}</h1>
<p style="font-family:Georgia,'Times New Roman',serif;font-size:15px;color:#5c4a45;line-height:1.7;margin:0 0 26px;">A customer {{ $isUpdate ? 'updated their' : 'left a' }} review. It is awaiting your approval and won't show on the site until you approve it.</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F1E4D3;border-left:3px solid #C9A96F;margin:0 0 28px;"><tr>
<td style="padding:22px 24px;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-family:Arial,Helvetica,sans-serif;">
        <tr>
            <td style="padding:0 0 4px;font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Product</td>
            <td align="right" style="padding:0 0 4px;font-size:15px;font-weight:bold;color:#371220;">{{ $review->product->name ?? '—' }}</td>
        </tr>
        <tr>
            <td style="padding:12px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Reviewer</td>
            <td align="right" style="padding:12px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:14px;color:#371220;">{{ $review->user->name ?? 'Customer' }}</td>
        </tr>
        <tr>
            <td style="padding:12px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Rating</td>
            <td align="right" style="padding:12px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:16px;letter-spacing:2px;">
                <span style="color:#C9A96F;">{{ str_repeat('★', (int) $review->rating) }}</span><span style="color:rgba(55,18,32,0.22);">{{ str_repeat('★', 5 - (int) $review->rating) }}</span>
                <span style="font-size:12px;color:#8a7266;">&nbsp;{{ $review->rating }}/5</span>
            </td>
        </tr>
        @if($review->title)
        <tr>
            <td style="padding:12px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Title</td>
            <td align="right" style="padding:12px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:13px;color:#371220;">{{ $review->title }}</td>
        </tr>
        @endif
        <tr>
            <td colspan="2" style="padding:12px 0 4px;border-top:1px solid rgba(55,18,32,0.10);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#8a7266;">Comment</td>
        </tr>
        <tr>
            <td colspan="2" style="font-family:Georgia,'Times New Roman',serif;font-size:14px;color:#5c4a45;line-height:1.6;">{{ $review->comment }}</td>
        </tr>
    </table>
</td>
</tr></table>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:6px 0 0;"><tr><td align="center">
    <a href="{{ config('app.url') }}/admin/reviews" style="display:inline-block;background-color:#371220;color:#FAF5ED;text-decoration:none;padding:15px 42px;font-family:Arial,Helvetica,sans-serif;font-size:10px;font-weight:bold;letter-spacing:3px;text-transform:uppercase;">Moderate Reviews</a>
</td></tr></table>
@endsection
