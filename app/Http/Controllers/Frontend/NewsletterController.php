<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        NewsletterSubscriber::firstOrCreate(
            ['email' => $request->email],
            ['is_active' => true, 'subscribed_at' => now()]
        );

        return back()->with('success', 'You\'ve been subscribed! Welcome to the Aurachell Circle.');
    }
}
