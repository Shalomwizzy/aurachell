<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminDeliveryNotificationMail;
use App\Mail\DeliveryCompletedMail;
use App\Mail\OrderShippedMail;
use App\Mail\OrderStatusUpdateMail;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'items'])->latest();

        if ($search = $request->q) {
            $query->where(fn ($q) => $q
                ->where('order_number', 'like', "%{$search}%")
                ->orWhere('tracking_code', 'like', "%{$search}%")
                ->orWhere('guest_email', 'like', "%{$search}%")
                ->orWhere('guest_name', 'like', "%{$search}%")
                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")));
        }

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        if ($payment = $request->payment_status) {
            $query->where('payment_status', $payment);
        }

        $orders = $query->paginate(25)->withQueryString();

        $stats = [
            'pending'                   => Order::where('status', 'pending')->count(),
            'pending_bank_confirmation' => Order::where('status', 'pending_bank_confirmation')->count(),
            'processing'                => Order::where('status', 'processing')->count(),
            'shipped'                   => Order::where('status', 'shipped')->count(),
            'today_revenue'             => Order::where('payment_status', 'paid')->whereDate('created_at', today())->sum('total'),
            'month_revenue'             => Order::where('payment_status', 'paid')->whereMonth('created_at', now()->month)->sum('total'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.product.primaryImage', 'statusHistory', 'payment']);

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'tracking_code' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $order->update($data);

        return back()->with('success', 'Order updated.');
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,processing,packed,shipped,out_for_delivery,delivered,cancelled,refunded',
            'note' => 'nullable|string|max:300',
        ]);

        $order->update(['status' => $request->status]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $request->status,
            'note' => $request->note ?? ucfirst(str_replace('_', ' ', $request->status)),
            'changed_by' => auth()->id(),
        ]);

        // Send status notification email to customer
        $email = $order->user?->email ?? $order->guest_email;
        if ($email) {
            try {
                $order->load('items');
                $mailable = match ($request->status) {
                    'shipped' => new OrderShippedMail($order),
                    'delivered' => new DeliveryCompletedMail($order->user ?? new User(['name' => $order->guest_name, 'email' => $order->guest_email]), $order),
                    'processing', 'packed', 'out_for_delivery', 'cancelled', 'refunded' => new OrderStatusUpdateMail($order),
                    default => null,
                };
                if ($mailable) {
                    Mail::to($email)->send($mailable);
                }
            } catch (\Exception $e) {
                Log::error('Order status email failed: '.$e->getMessage());
            }
        }

        // Notify admin when an order is delivered
        if ($request->status === 'delivered') {
            $adminEmail = config('services.admin.email');
            if ($adminEmail) {
                try {
                    $order->loadMissing('items');
                    Mail::to($adminEmail)->send(new AdminDeliveryNotificationMail($order));
                } catch (\Exception $e) {
                    Log::error('Admin delivery notification failed: '.$e->getMessage());
                }
            }
        }

        return back()->with('success', 'Order status updated to '.ucfirst(str_replace('_', ' ', $request->status)).'.');
    }

    public function invoice(Order $order): mixed
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.invoice', compact('order'));
    }
}
