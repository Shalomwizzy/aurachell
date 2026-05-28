<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('wishlist')
            ->whereHas('wishlist')
            ->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(25)->withQueryString();

        // Eager-load wishlist items with product for the expanded rows
        $users->load(['wishlist.product']);

        $stats = [
            'total_entries'   => Wishlist::count(),
            'users_with_list' => User::whereHas('wishlist')->count(),
            'top_product'     => Wishlist::with('product')
                                    ->selectRaw('product_id, count(*) as times_saved')
                                    ->groupBy('product_id')
                                    ->orderByDesc('times_saved')
                                    ->first()?->product?->name ?? 'N/A',
        ];

        return view('admin.wishlists.index', compact('users', 'stats'));
    }
}
