<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ProductRequestFulfilledMail;
use App\Models\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProductRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductRequest::latest();

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $requests = $query->paginate(20)->withQueryString();
        $counts = [
            'all' => ProductRequest::count(),
            'pending' => ProductRequest::where('status', 'pending')->count(),
            'viewed' => ProductRequest::where('status', 'viewed')->count(),
            'fulfilled' => ProductRequest::where('status', 'fulfilled')->count(),
        ];

        return view('admin.product-requests.index', compact('requests', 'counts'));
    }

    public function show(ProductRequest $productRequest)
    {
        if ($productRequest->status === 'pending') {
            $productRequest->update(['status' => 'viewed']);
        }

        return view('admin.product-requests.show', compact('productRequest'));
    }

    public function update(Request $request, ProductRequest $productRequest)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,viewed,fulfilled',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $wasNotFulfilled = $productRequest->status !== 'fulfilled';
        $productRequest->update($data);

        if ($wasNotFulfilled && $data['status'] === 'fulfilled') {
            try {
                Mail::to($productRequest->customer_email)->send(new ProductRequestFulfilledMail($productRequest));
            } catch (\Exception $e) {
                Log::error('Product request fulfilled mail failed: '.$e->getMessage());
            }
        }

        return back()->with('success', $data['status'] === 'fulfilled' ? 'Request marked as fulfilled and customer notified.' : 'Request updated.');
    }

    public function destroy(ProductRequest $productRequest)
    {
        if ($productRequest->image_path) {
            @unlink(public_path('images/product-requests/'.basename($productRequest->image_path)));
        }
        $productRequest->delete();

        return redirect()->route('admin.product-requests.index')->with('success', 'Request deleted.');
    }
}
