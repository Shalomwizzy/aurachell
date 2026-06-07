<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ProductRequest;
use App\Traits\SecureFileUpload;
use Illuminate\Http\Request;

class ProductRequestController extends Controller
{
    use SecureFileUpload;

    public function create()
    {
        return view('frontend.product-request');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name'    => 'required|string|max:100',
            'customer_email'   => 'required|email|max:150',
            'product_name'     => 'required|string|max:200',
            'description'      => 'nullable|string|max:1000',
            'scent_preference' => 'nullable|string|max:200',
            'budget'           => 'nullable|string|max:100',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $ext       = $this->safeExtension($file, ['jpg', 'jpeg', 'png', 'webp']);
            $filename  = uniqid('req_') . '.' . $ext;
            $file->move(public_path('images/product-requests'), $filename);
            $imagePath = $filename;
        }

        ProductRequest::create([
            'user_id'          => auth()->id(),
            'customer_name'    => $data['customer_name'],
            'customer_email'   => $data['customer_email'],
            'product_name'     => $data['product_name'],
            'description'      => $data['description'] ?? null,
            'scent_preference' => $data['scent_preference'] ?? null,
            'budget'           => $data['budget'] ?? null,
            'image_path'       => $imagePath,
        ]);

        return back()->with('success', 'Your request has been submitted! We will review it and get back to you.');
    }
}
