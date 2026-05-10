<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function success(string $orderNumber)
    {
        $query = Order::where('order_number', $orderNumber)->with('items.product');

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            // Guests may only see their own order via the session token written during payment callback
            if (session('last_order_number') !== $orderNumber) {
                abort(404);
            }
        }

        $order = $query->firstOrFail();

        return view('frontend.order-success', compact('order'));
    }

    public function trackForm()
    {
        return view('frontend.track-order');
    }

    public function track(Request $request)
    {
        $request->validate([
            'tracking_code' => 'required|string|max:20',
            'email' => 'required|email',
        ]);

        $order = Order::where('tracking_code', strtoupper($request->tracking_code))
            ->where(function ($q) use ($request) {
                $q->where('guest_email', $request->email)
                    ->orWhereHas('user', fn ($u) => $u->where('email', $request->email));
            })
            ->with(['items.product', 'statusHistory'])
            ->first();

        if (! $order) {
            return back()->withErrors(['tracking_code' => 'No order found with that tracking code and email combination.']);
        }

        return view('frontend.track-order', compact('order'));
    }
}
