<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PersonalMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('orders')
            ->withSum(['orders' => fn ($q) => $q->where('payment_status', 'paid')], 'total')
            ->whereDoesntHave('roles')
            ->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate(25)->withQueryString();

        $stats = [
            'total' => User::whereDoesntHave('roles')->count(),
            'today' => User::whereDoesntHave('roles')->whereDate('created_at', today())->count(),
            'this_month' => User::whereDoesntHave('roles')->whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    public function show(User $user)
    {
        $user->load(['orders.items.product', 'addresses', 'reviews.product']);
        $user->loadCount('orders');
        $user->loadSum(['orders' => fn ($q) => $q->where('payment_status', 'paid')], 'total');

        return view('admin.customers.show', compact('user'));
    }

    public function sendEmail(Request $request, User $user)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:200',
            'body' => 'required|string|max:5000',
            'coupon_code' => 'nullable|string|max:50',
        ]);

        try {
            Mail::to($user->email)->send(new PersonalMail(
                $user->name,
                $data['subject'],
                $data['body'],
                $data['coupon_code'] ?: null,
            ));
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to send email: '.$e->getMessage());
        }

        return back()->with('success', 'Email sent to '.$user->name.' successfully.');
    }

    public function toggleBlock(User $user)
    {
        $newValue = ! ($user->is_blocked ?? false);
        $user->update(['is_blocked' => $newValue]);

        return back()->with('success', $newValue ? 'Customer blocked.' : 'Customer unblocked.');
    }
}
