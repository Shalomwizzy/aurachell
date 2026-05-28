<?php

namespace App\Console\Commands;

use App\Mail\BirthdayMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class SendBirthdayEmail extends Command
{
    protected $signature = 'emails:birthday';

    protected $description = 'Send birthday emails to users whose birthday is today';

    public function handle(): int
    {
        if (! Schema::hasColumn('users', 'birthday')) {
            $this->warn('birthday column does not exist on users table. Run migrations first.');
            return self::SUCCESS;
        }

        $today = now()->format('m-d');

        $users = User::whereNotNull('birthday')
            ->whereRaw("DATE_FORMAT(birthday, '%m-%d') = ?", [$today])
            ->get();

        $sent = 0;
        foreach ($users as $user) {
            Mail::to($user->email)->queue(new BirthdayMail($user));
            $sent++;
            $this->line("Birthday email → {$user->email}");
        }

        $this->info("Birthday emails queued for {$sent} users.");

        return self::SUCCESS;
    }
}
