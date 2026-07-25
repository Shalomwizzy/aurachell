<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ReturnRequestMail;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReturnController extends Controller
{
    public function store(Request $request, string $orderNumber): RedirectResponse
    {
        $order = auth()->user()->orders()
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        abort_if($order->status !== 'delivered', 403, 'Only delivered orders can be returned.');
        abort_if($order->returnRequest()->exists(), 422, 'A return request already exists for this order.');

        $windowDays = (int) (Setting::get('return_window_days') ?? 3);
        $deliveredAt = $order->delivered_at ?? $order->updated_at;

        abort_if(
            $windowDays > 0 && $deliveredAt->diffInDays(now()) > $windowDays,
            422,
            "The return window for this order has closed ({$windowDays} days after delivery)."
        );

        $data = $request->validate([
            'reason' => ['required', 'string', 'max:100'],
            'details' => ['nullable', 'string', 'max:1000'],
        ]);

        $returnRequest = $order->returnRequest()->create([
            'user_id' => auth()->id(),
            'reason' => $data['reason'],
            'details' => $data['details'] ?? null,
            'status' => 'pending',
        ]);

        Mail::to(auth()->user()->email)
            ->send(new ReturnRequestMail($returnRequest->load('order'), 'submitted'));

        return back()->with('success', 'Your return request has been submitted. We will review it within 2–3 business days.');
    }
}
