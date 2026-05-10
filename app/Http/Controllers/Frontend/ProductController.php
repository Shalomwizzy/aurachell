<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::active()->where('slug', $slug)
            ->with(['category', 'images', 'variants', 'reviews.user'])
            ->firstOrFail();

        $product->increment('views_count');

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('primaryImage')
            ->limit(4)
            ->get();

        return view('frontend.product', compact('product', 'related'));
    }
}
