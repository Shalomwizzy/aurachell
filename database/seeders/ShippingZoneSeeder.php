<?php

namespace Database\Seeders;

use App\Models\ShippingZone;
use Illuminate\Database\Seeder;

class ShippingZoneSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            [
                'name' => 'Lekki / Island',
                'sort_order' => 1,
                'cities' => ['Lekki', 'Ikate', 'Chevron', 'VI', 'Ikoyi', 'Orchid'],
                'rates' => [
                    ['method' => 'standard', 'price' => 2000, 'min_days' => 1, 'max_days' => 2],
                    ['method' => 'express',  'price' => 3500, 'min_days' => 1, 'max_days' => 1],
                ],
            ],
            [
                'name' => 'Lagos Mainland',
                'sort_order' => 2,
                'cities' => ['Yaba', 'Surulere', 'Ikeja', 'Gbagada', 'Maryland'],
                'rates' => [
                    ['method' => 'standard', 'price' => 3000, 'min_days' => 1, 'max_days' => 3],
                    ['method' => 'express',  'price' => 5000, 'min_days' => 1, 'max_days' => 2],
                ],
            ],
        ];

        foreach ($zones as $zoneData) {
            $rates = $zoneData['rates'];
            unset($zoneData['rates']);

            $zone = ShippingZone::updateOrCreate(['name' => $zoneData['name']], $zoneData);

            foreach ($rates as $rate) {
                $zone->rates()->updateOrCreate(['method' => $rate['method']], $rate);
            }
        }
    }
}
