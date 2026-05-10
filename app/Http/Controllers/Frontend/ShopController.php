<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with(['primaryImage', 'category', 'reviews']);

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(fn ($q) => $q->where('name', 'like', "%$search%")->orWhere('scent_notes', 'like', "%$search%")->orWhere('short_description', 'like', "%$search%"));
        }
        if ($request->filled('min')) {
            $query->where('price', '>=', $request->min);
        }
        if ($request->filled('max')) {
            $query->where('price', '<=', $request->max);
        }
        if ($request->in_stock) {
            $query->where('stock_quantity', '>', 0);
        }

        $sort = $request->get('sort', 'newest');
        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'featured' => $query->orderByDesc('is_featured')->orderByDesc('views_count'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::active()->orderBy('sort_order')->get();

        return view('frontend.shop', compact('products', 'categories'));
    }
}
