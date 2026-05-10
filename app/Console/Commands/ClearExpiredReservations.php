<?php

namespace App\Console\Commands;

use App\Models\StockReservation;
use Illuminate\Console\Command;

class ClearExpiredReservations extends Command
{
    protected $signature = 'stock:clear-reservations';

    protected $description = 'Delete expired stock reservations so held stock is returned to available.';

    public function handle(): int
    {
        $deleted = StockReservation::where('expires_at', '<=', now())->delete();
        $this->info("Cleared {$deleted} expired reservation(s).");

        return self::SUCCESS;
    }
}
