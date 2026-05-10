<?php

namespace App\Listeners;

use App\Mail\WelcomeMail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmailAfterVerification
{
    public function handle(Verified $event): void
    {
        try {
            Mail::to($event->user->email)->send(new WelcomeMail($event->user));
        } catch (\Exception $e) {
            Log::error('Welcome email failed after verification: '.$e->getMessage());
        }
    }
}
