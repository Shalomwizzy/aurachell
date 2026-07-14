<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE products MODIFY burn_time_hours VARCHAR(100) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE products MODIFY burn_time_hours INT UNSIGNED NULL');
    }
};
