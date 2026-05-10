<?php

namespace App\Console\Commands;

use App\Mail\FestiveMail;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendFestiveEmail extends Command
{
    protected $signature = 'emails:festive {--event=christmas : Event name (christmas|easter|eid|ramadan|new_year)}';

    protected $description = 'Send a festive season email to all users and subscribers';

    public function handle(): int
    {
        $event = $this->option('event');
        $valid = ['christmas', 'easter', 'eid', 'ramadan', 'new_year'];

        if (! in_array($event, $valid)) {
            $this->error('Invalid event. Use: '.implode(', ', $valid));

            return self::FAILURE;
        }

        $users = User::where('is_guest', false)->whereNotNull('email')->get();
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            Mail::to($user->email)->queue(new FestiveMail($user, $event));
            $bar->advance();
        }

        $userEmails = $users->pluck('email')->toArray();
        NewsletterSubscriber::whereNotIn('email', $userEmails)->each(function ($sub) use ($event) {
            $ghost = new User(['name' => 'Valued Customer', 'email' => $sub->email]);
            Mail::to($sub->email)->queue(new FestiveMail($ghost, $event));
        });

        $bar->finish();
        $this->newLine();
        $this->info("Festive ({$event}) email queued for {$users->count()} users.");

        return self::SUCCESS;
    }
}
