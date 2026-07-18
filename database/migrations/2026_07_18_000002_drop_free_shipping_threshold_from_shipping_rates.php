<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_rates', function (Blueprint $table) {
            if (Schema::hasColumn('shipping_rates', 'free_shipping_threshold')) {
                $table->dropColumn('free_shipping_threshold');
            }
        });
    }

    public function down(): void
    {
        Schema::table('shipping_rates', function (Blueprint $table) {
            $table->decimal('free_shipping_threshold', 10, 2)->default(0);
        });
    }
};
