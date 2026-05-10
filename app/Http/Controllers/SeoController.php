<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $products = Product::where('is_active', true)->select('slug', 'updated_at')->get();
        $categories = Category::select('slug', 'updated_at')->get();

        $content = view('seo.sitemap', compact('products', 'categories'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    public function robots(): Response
    {
        $content = view('seo.robots')->render();

        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
