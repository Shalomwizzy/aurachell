<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCategories = Category::active()->whereNull('parent_id')->orderBy('sort_order')->limit(3)->get();
        $bestsellers = Product::active()->featured()->with(['primaryImage', 'reviews'])->orderByDesc('views_count')->limit(8)->get();
        $reviews = Review::approved()->with('user')->latest()->limit(6)->get();

        return view('frontend.home', compact('featuredCategories', 'bestsellers', 'reviews'));
    }
}
