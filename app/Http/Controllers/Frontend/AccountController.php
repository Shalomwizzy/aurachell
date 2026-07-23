<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\AdminReviewNotificationMail;
use App\Models\Address;
use App\Models\Product;
use App\Models\Review;
use App\Models\Wishlist;
use App\Traits\SecureFileUpload;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AccountController extends Controller
{
    use SecureFileUpload;
    public function overview()
    {
        $user = auth()->user();
        $recentOrders = $user->orders()->latest()->limit(5)->get();
        $wishlistCount = $user->wishlist()->count();

        return view('account.overview', compact('user', 'recentOrders', 'wishlistCount'));
    }

    public function orders()
    {
        $orders = auth()->user()->orders()->with('items')->latest()->paginate(10);

        return view('account.orders', compact('orders'));
    }

    public function orderDetail(string $orderNumber)
    {
        $order = auth()->user()->orders()->where('order_number', $orderNumber)->with(['items.product', 'statusHistory'])->firstOrFail();

        return view('account.order-detail', compact('order'));
    }

    public function downloadInvoice(string $orderNumber)
    {
        $order = auth()->user()->orders()->where('order_number', $orderNumber)->with('items.product')->firstOrFail();
        $pdf = app(Pdf::class)->loadView('admin.orders.invoice', compact('order'));

        return $pdf->download("invoice-{$orderNumber}.pdf");
    }

    public function track()
    {
        $orders = auth()->user()->orders()->whereIn('status', ['paid', 'processing', 'shipped', 'out_for_delivery', 'delivered', 'cancelled'])->latest()->get();

        return view('account.track', compact('orders'));
    }

    public function addresses()
    {
        $addresses = auth()->user()->addresses()->get();

        return view('account.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'address_line_1' => 'required|string|max:200',
            'address_line_2' => 'nullable|string|max:200',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'is_default' => 'boolean',
        ]);

        if (! empty($data['is_default'])) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }
        auth()->user()->addresses()->create($data);

        return back()->with('success', 'Address saved.');
    }

    public function updateAddress(Request $request, Address $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);
        $data = $request->validate([
            'full_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:200',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
        ]);
        $address->update($data);

        return back()->with('success', 'Address updated.');
    }

    public function destroyAddress(Address $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);
        $address->delete();

        return back()->with('success', 'Address removed.');
    }

    public function setDefaultAddress(Address $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);
        auth()->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Default address updated.');
    }

    public function wishlist()
    {
        $items = auth()->user()->wishlist()->with('product.primaryImage')->paginate(12);

        return view('account.wishlist', compact('items'));
    }

    public function toggleWishlist(Product $product)
    {
        $user = auth()->user();
        $existing = $user->wishlist()->where('product_id', $product->id)->first();
        if ($existing) {
            $existing->delete();

            return back()->with('success', 'Removed from wishlist.');
        }
        Wishlist::create(['user_id' => $user->id, 'product_id' => $product->id]);

        return back()->with('success', 'Added to wishlist!');
    }

    public function reviews()
    {
        $reviews = auth()->user()->reviews()->with('product')->latest()->paginate(10);

        return view('account.reviews', compact('reviews'));
    }

    public function storeReview(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:150',
            'comment' => 'required|string|max:1000',
        ]);

        // One review per product per user — resubmitting replaces the old one
        // and sends it back through moderation
        $existing = Review::where('user_id', auth()->id())
            ->where('product_id', $data['product_id'])
            ->first();

        if ($existing) {
            $existing->update([
                'rating' => $data['rating'],
                'title' => $data['title'] ?? null,
                'comment' => $data['comment'],
                'is_approved' => false,
            ]);

            $this->notifyAdminOfReview($existing, true);

            return back()->with('success', 'Your review has been updated! It will appear after approval.');
        }

        $review = Review::create($data + ['user_id' => auth()->id()]);

        $this->notifyAdminOfReview($review, false);

        return back()->with('success', 'Review submitted! It will appear after approval.');
    }

    private function notifyAdminOfReview(Review $review, bool $isUpdate): void
    {
        $adminEmail = config('services.admin.email');
        if (! $adminEmail) {
            return;
        }

        try {
            $review->loadMissing('product', 'user');
            Mail::to($adminEmail)->send(new AdminReviewNotificationMail($review, $isUpdate));
        } catch (\Exception $e) {
            Log::error('Admin review notification failed: '.$e->getMessage());
        }
    }

    public function profile()
    {
        return view('account.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'birthday' => 'nullable|date|before:today',
            'avatar' => 'nullable|image|max:2048',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                @unlink(public_path('images/avatars/' . basename($user->avatar)));
            }
            $file     = $request->file('avatar');
            $ext      = $this->safeExtension($file, ['jpg', 'jpeg', 'png', 'webp']);
            $filename = uniqid('avatar_') . '.' . $ext;
            $file->move(public_path('images/avatars'), $filename);
            $data['avatar'] = $filename;
        }

        if ($request->filled('current_password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            if ($request->filled('password')) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
        } else {
            unset($data['password']);
        }

        unset($data['current_password']);
        $user->update($data);

        return back()->with('success', 'Profile updated.');
    }
}
