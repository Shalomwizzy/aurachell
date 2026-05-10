<?php

namespace App\Console\Commands;

use App\Mail\NewProductMail;
use App\Models\NewsletterSubscriber;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendNewProductEmail extends Command
{
    protected $signature = 'emails:new-product {product : Product ID or slug}';

    protected $description = 'Send new product announcement to all subscribers and registered users';

    public function handle(): int
    {
        $idOrSlug = $this->argument('product');
        $product = is_numeric($idOrSlug)
            ? Product::findOrFail($idOrSlug)
            : Product::where('slug', $idOrSlug)->firstOrFail();

        $users = User::whereNotNull('email')
            ->where('is_guest', false)
            ->get();

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            Mail::to($user->email)->queue(new NewProductMail($user, $product));
            $bar->advance();
        }

        // Also notify newsletter subscribers who aren't registered users
        $userEmails = $users->pluck('email')->toArray();
        NewsletterSubscriber::whereNotIn('email', $userEmails)->each(function ($sub) use ($product) {
            $ghost = new User(['name' => 'Valued Customer', 'email' => $sub->email]);
            Mail::to($sub->email)->queue(new NewProductMail($ghost, $product));
        });

        $bar->finish();
        $this->newLine();
        $this->info("New product email queued for {$users->count()} users.");

        return self::SUCCESS;
    }
}
