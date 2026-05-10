<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class EmailCampaignController extends Controller
{
    public function index(): View
    {
        $products = Product::active()->latest()->get(['id', 'name']);

        return view('admin.email-campaigns.index', compact('products'));
    }

    public function sendNewProduct(Request $request): RedirectResponse
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        try {
            Artisan::call('emails:new-product', ['product' => $request->product_id]);
            $output = Artisan::output();

            return back()->with('success', 'New product emails sent. '.trim($output));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send: '.$e->getMessage());
        }
    }

    public function sendWishlistReminder(): RedirectResponse
    {
        try {
            Artisan::call('emails:wishlist-reminder');
            $output = Artisan::output();

            return back()->with('success', 'Wishlist reminder emails sent. '.trim($output));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send: '.$e->getMessage());
        }
    }

    public function sendCartReminder(): RedirectResponse
    {
        try {
            Artisan::call('emails:cart-reminder');
            $output = Artisan::output();

            return back()->with('success', 'Cart abandonment emails sent. '.trim($output));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send: '.$e->getMessage());
        }
    }

    public function sendFestive(Request $request): RedirectResponse
    {
        $request->validate([
            'event' => 'required|in:christmas,easter,eid,ramadan,new_year',
        ]);

        try {
            Artisan::call('emails:festive', ['--event' => $request->event]);
            $output = Artisan::output();

            return back()->with('success', 'Festive emails sent for '.ucfirst(str_replace('_', ' ', $request->event)).'. '.trim($output));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send: '.$e->getMessage());
        }
    }

    public function sendNewMonth(): RedirectResponse
    {
        try {
            Artisan::call('emails:new-month');
            $output = Artisan::output();

            return back()->with('success', 'New month emails sent. '.trim($output));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send: '.$e->getMessage());
        }
    }
}
