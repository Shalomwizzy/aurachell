<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product'])->latest();

        if ($request->get('status') === 'pending') {
            $query->where('is_approved', false);
        } elseif ($request->get('status') === 'approved') {
            $query->where('is_approved', true);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('product', fn ($p) => $p->where('name', 'like', "%{$search}%"));
            });
        }

        $reviews = $query->paginate(25)->withQueryString();
        $pendingCount = Review::where('is_approved', false)->count();

        return view('admin.reviews.index', compact('reviews', 'pendingCount'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => ! $review->is_approved]);
        $msg = $review->is_approved ? 'Review approved.' : 'Review unapproved.';

        return back()->with('success', $msg);
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}
