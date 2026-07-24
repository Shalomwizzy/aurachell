<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\AdminPreorderNotificationMail;
use App\Mail\PreorderConfirmationMail;
use App\Models\Preorder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PreorderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'customer_name' => 'required|string|max:100',
            'customer_email' => 'required|email:filter|max:150',
            'customer_phone' => 'nullable|string|max:30',
            'quantity' => 'nullable|integer|min:1|max:99',
            'note' => 'nullable|string|max:500',
        ]);

        $product = Product::active()->findOrFail($data['product_id']);

        if ($product->isInStock()) {
            return back()->with('error', 'This product is back in stock — you can order it right away.');
        }

        $alreadyExists = Preorder::where('product_id', $product->id)
            ->where('customer_email', strtolower($data['customer_email']))
            ->where('status', 'pending')
            ->exists();

        if ($alreadyExists) {
            return back()->with('error', 'You already have a pending pre-order for this product. We will contact you as soon as it is back.');
        }

        $preorder = Preorder::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'customer_name' => $data['customer_name'],
            'customer_email' => strtolower($data['customer_email']),
            'customer_phone' => $data['customer_phone'] ?? null,
            'quantity' => $data['quantity'] ?? 1,
            'note' => $data['note'] ?? null,
        ]);

        // Customer confirmation email
        try {
            Mail::to($preorder->customer_email)->send(new PreorderConfirmationMail($preorder));
        } catch (\Exception $e) {
            Log::error('Preorder confirmation email failed: '.$e->getMessage());
        }

        // Admin notification (hello@ only)
        try {
            $adminEmail = config('services.admin.email');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new AdminPreorderNotificationMail($preorder));
            }
        } catch (\Exception $e) {
            Log::error('Admin preorder notification failed: '.$e->getMessage());
        }

        return back()->with('success', 'Pre-order received! A confirmation has been sent to your email — we will contact you the moment it is back in stock.');
    }
}
