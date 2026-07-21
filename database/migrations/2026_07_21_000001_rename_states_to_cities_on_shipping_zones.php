<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('shipping_zones', 'states') && ! Schema::hasColumn('shipping_zones', 'cities')) {
            Schema::table('shipping_zones', function (Blueprint $table) {
                $table->renameColumn('states', 'cities');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('shipping_zones', 'cities') && ! Schema::hasColumn('shipping_zones', 'states')) {
            Schema::table('shipping_zones', function (Blueprint $table) {
                $table->renameColumn('cities', 'states');
            });
        }
    }
};
