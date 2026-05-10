<?php

namespace App\Console\Commands;

use App\Mail\NewMonthMail;
use App\Models\NewsletterSubscriber;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendNewMonthEmail extends Command
{
    protected $signature = 'emails:new-month';

    protected $description = 'Send happy new month email with featured products to all users';

    public function handle(): int
    {
        $featured = Product::where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(4)
            ->get();

        if ($featured->isEmpty()) {
            $featured = Product::where('is_active', true)->latest()->take(4)->get();
        }

        $users = User::where('is_guest', false)->whereNotNull('email')->get();
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            Mail::to($user->email)->queue(new NewMonthMail($user, $featured));
            $bar->advance();
        }

        $userEmails = $users->pluck('email')->toArray();
        NewsletterSubscriber::whereNotIn('email', $userEmails)->each(function ($sub) use ($featured) {
            $ghost = new User(['name' => 'Valued Customer', 'email' => $sub->email]);
            Mail::to($sub->email)->queue(new NewMonthMail($ghost, $featured));
        });

        $bar->finish();
        $this->newLine();
        $this->info("New month email queued for {$users->count()} users.");

        return self::SUCCESS;
    }
}
