<?php

namespace App\Console\Commands;

use App\Mail\WishlistReminderMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWishlistReminder extends Command
{
    protected $signature = 'emails:wishlist-reminder';

    protected $description = 'Send wishlist reminder emails to users with saved items';

    public function handle(): int
    {
        $users = User::whereHas('wishlist')
            ->with(['wishlist.product' => fn ($q) => $q->where('is_active', true)])
            ->get();

        $sent = 0;
        foreach ($users as $user) {
            $items = $user->wishlist->filter(fn ($w) => $w->product !== null);
            if ($items->isEmpty()) {
                continue;
            }

            Mail::to($user->email)->queue(new WishlistReminderMail($user, $items));
            $sent++;
        }

        $this->info("Wishlist reminder queued for {$sent} users.");

        return self::SUCCESS;
    }
}
