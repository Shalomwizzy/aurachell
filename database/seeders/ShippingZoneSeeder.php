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
                'name' => 'Lagos',
                'sort_order' => 1,
                'states' => ['Lagos'],
                'rates' => [
                    ['method' => 'standard', 'price' => 2000, 'free_shipping_threshold' => 50000, 'min_days' => 1, 'max_days' => 2],
                    ['method' => 'express',  'price' => 3500, 'free_shipping_threshold' => 0,     'min_days' => 1, 'max_days' => 1],
                ],
            ],
            [
                'name' => 'South-West',
                'sort_order' => 2,
                'states' => ['Ogun', 'Oyo', 'Osun', 'Ekiti', 'Ondo'],
                'rates' => [
                    ['method' => 'standard', 'price' => 3000, 'free_shipping_threshold' => 50000, 'min_days' => 2, 'max_days' => 3],
                    ['method' => 'express',  'price' => 5000, 'free_shipping_threshold' => 0,     'min_days' => 1, 'max_days' => 2],
                ],
            ],
            [
                'name' => 'South-South',
                'sort_order' => 3,
                'states' => ['Rivers', 'Delta', 'Edo', 'Cross River', 'Akwa Ibom', 'Bayelsa'],
                'rates' => [
                    ['method' => 'standard', 'price' => 4000, 'free_shipping_threshold' => 60000, 'min_days' => 2, 'max_days' => 4],
                    ['method' => 'express',  'price' => 6500, 'free_shipping_threshold' => 0,     'min_days' => 1, 'max_days' => 2],
                ],
            ],
            [
                'name' => 'South-East',
                'sort_order' => 4,
                'states' => ['Anambra', 'Imo', 'Abia', 'Enugu', 'Ebonyi'],
                'rates' => [
                    ['method' => 'standard', 'price' => 4000, 'free_shipping_threshold' => 60000, 'min_days' => 2, 'max_days' => 4],
                    ['method' => 'express',  'price' => 6500, 'free_shipping_threshold' => 0,     'min_days' => 1, 'max_days' => 2],
                ],
            ],
            [
                'name' => 'North-Central / FCT',
                'sort_order' => 5,
                'states' => ['FCT', 'Abuja', 'Kogi', 'Kwara', 'Benue', 'Nasarawa', 'Niger', 'Plateau'],
                'rates' => [
                    ['method' => 'standard', 'price' => 4500, 'free_shipping_threshold' => 60000, 'min_days' => 3, 'max_days' => 5],
                    ['method' => 'express',  'price' => 7000, 'free_shipping_threshold' => 0,     'min_days' => 2, 'max_days' => 3],
                ],
            ],
            [
                'name' => 'North-West',
                'sort_order' => 6,
                'states' => ['Kano', 'Kaduna', 'Katsina', 'Sokoto', 'Kebbi', 'Zamfara', 'Jigawa'],
                'rates' => [
                    ['method' => 'standard', 'price' => 5000, 'free_shipping_threshold' => 70000, 'min_days' => 3, 'max_days' => 6],
                    ['method' => 'express',  'price' => 8000, 'free_shipping_threshold' => 0,     'min_days' => 2, 'max_days' => 3],
                ],
            ],
            [
                'name' => 'North-East',
                'sort_order' => 7,
                'states' => ['Borno', 'Gombe', 'Adamawa', 'Yobe', 'Bauchi', 'Taraba'],
                'rates' => [
                    ['method' => 'standard', 'price' => 5500, 'free_shipping_threshold' => 70000, 'min_days' => 4, 'max_days' => 7],
                    ['method' => 'express',  'price' => 9000, 'free_shipping_threshold' => 0,     'min_days' => 2, 'max_days' => 4],
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
