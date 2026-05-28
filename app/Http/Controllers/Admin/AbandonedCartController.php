<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CartAbandonmentMail;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class AbandonedCartController extends Controller
{
    public function index(Request $request)
    {
        $query = Cart::with(['user', 'items.product'])
            ->whereHas('user')
            ->whereHas('items')
            ->where('updated_at', '<=', now()->subHours(24))
            ->where('updated_at', '>=', now()->subDays(30))
            ->latest('updated_at');

        if ($search = $request->get('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($age = $request->get('age')) {
            match ($age) {
                '48h'  => $query->where('updated_at', '<=', now()->subHours(48)),
                '72h'  => $query->where('updated_at', '<=', now()->subHours(72)),
                'week' => $query->where('updated_at', '<=', now()->subDays(7)),
                default => null,
            };
        }

        $carts = $query->paginate(20)->withQueryString();

        $stats = [
            'total'  => Cart::whereHas('user')->whereHas('items')
                            ->where('updated_at', '<=', now()->subHours(24))
                            ->where('updated_at', '>=', now()->subDays(30))
                            ->count(),
            'value'  => Cart::with('items')->whereHas('user')->whereHas('items')
                            ->where('updated_at', '<=', now()->subHours(24))
                            ->where('updated_at', '>=', now()->subDays(30))
                            ->get()
                            ->sum(fn ($c) => $c->total),
            'unreminded' => Cart::whereHas('user')->whereHas('items')
                                ->where('updated_at', '<=', now()->subHours(24))
                                ->where('updated_at', '>=', now()->subDays(30))
                                ->where(fn ($q) => $q->whereNull('last_reminder_at')
                                    ->orWhere('last_reminder_at', '<=', now()->subHours(24)))
                                ->count(),
        ];

        return view('admin.abandoned-carts.index', compact('carts', 'stats'));
    }

    public function sendReminder(Cart $cart)
    {
        if (! $cart->user || $cart->items->isEmpty()) {
            return back()->with('error', 'Cannot send reminder — cart has no user or no items.');
        }

        Mail::to($cart->user->email)
            ->send(new CartAbandonmentMail($cart->user, $cart->items));

        if (Schema::hasColumn('carts', 'last_reminder_at')) {
            $cart->update(['last_reminder_at' => now()]);
        }

        return back()->with('success', "Reminder sent to {$cart->user->name} ({$cart->user->email}).");
    }

    public function sendAll(Request $request)
    {
        $carts = Cart::with(['user', 'items.product'])
            ->whereHas('user')
            ->whereHas('items')
            ->where('updated_at', '<=', now()->subHours(24))
            ->where('updated_at', '>=', now()->subDays(30))
            ->where(fn ($q) => $q->whereNull('last_reminder_at')
                ->orWhere('last_reminder_at', '<=', now()->subHours(24)))
            ->get();

        $sent = 0;
        foreach ($carts as $cart) {
            if (! $cart->user || $cart->items->isEmpty()) {
                continue;
            }
            Mail::to($cart->user->email)
                ->send(new CartAbandonmentMail($cart->user, $cart->items));

            if (Schema::hasColumn('carts', 'last_reminder_at')) {
                $cart->update(['last_reminder_at' => now()]);
            }
            $sent++;
        }

        return back()->with('success', "Reminder sent to {$sent} customer" . ($sent === 1 ? '' : 's') . '.');
    }

    public function destroy(Cart $cart)
    {
        $cart->items()->delete();
        $cart->delete();

        return back()->with('success', 'Abandoned cart cleared.');
    }
}
