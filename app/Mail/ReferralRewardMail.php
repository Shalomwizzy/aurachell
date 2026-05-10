<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReferralRewardMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $referrer,
        public string $couponCode,
        public int $rewardPercent = 10,
        public int $validityDays = 90,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'You earned a reward — someone used your referral!');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.referral-reward', with: [
            'referrer' => $this->referrer,
            'couponCode' => $this->couponCode,
            'rewardPercent' => $this->rewardPercent,
            'validityDays' => $this->validityDays,
        ]);
    }
}
