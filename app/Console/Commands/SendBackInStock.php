<?php

namespace App\Console\Commands;

use App\Mail\BackInStockMail;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendBackInStock extends Command
{
    protected $signature = 'emails:back-in-stock {--product= : Product ID to notify about}';

    protected $description = 'Notify users when a wishlisted product comes back in stock';

    public function handle(): int
    {
        $productId = $this->option('product');

        $query = Product::where('stock_quantity', '>', 0)
            ->whereHas('wishlists');

        if ($productId) {
            $query->where('id', $productId);
        }

        $products = $query->with('wishlists.user')->get();

        $sent = 0;
        foreach ($products as $product) {
            foreach ($product->wishlists as $wish) {
                if (! $wish->user) {
                    continue;
                }

                // Only notify if the product was out of stock and is now back
                // Track via a flag on the wishlist — skip if already notified recently
                if ($wish->notified_at && $wish->notified_at->gt(now()->subDays(30))) {
                    continue;
                }

                Mail::to($wish->user->email)
                    ->queue(new BackInStockMail($wish->user, $product));

                // Mark as notified if the column exists
                if (isset($wish->notified_at)) {
                    $wish->update(['notified_at' => now()]);
                }

                $sent++;
                $this->line("Back-in-stock → {$wish->user->email} ({$product->name})");
            }
        }

        $this->info("Back-in-stock emails queued for {$sent} users.");

        return self::SUCCESS;
    }
}
