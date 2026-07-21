<?php

namespace App\Services;

use App\Models\ShippingZone;

class ShippingService
{
    public function getRatesForCity(string $city, float $subtotal): array
    {
        $zone = ShippingZone::forCity($city);

        if (! $zone) {
            return $this->fallback();
        }

        $zone->load('rates');
        $result = [];

        foreach ($zone->rates as $rate) {
            $result[$rate->method] = [
                'price' => (float) $rate->price,
                'delivery' => $rate->deliveryLabel(),
                'zone' => $zone->name,
            ];
        }

        // Guarantee both methods always present
        foreach (['standard', 'express'] as $method) {
            if (! isset($result[$method])) {
                $result[$method] = $this->fallbackRate($method);
            }
        }

        return $result;
    }

    public function calculate(string $city, float $subtotal, string $method): float
    {
        $rates = $this->getRatesForCity($city, $subtotal);

        return $rates[$method]['price'] ?? $this->fallbackRate($method)['price'];
    }

    private function fallback(): array
    {
        return [
            'standard' => $this->fallbackRate('standard'),
            'express' => $this->fallbackRate('express'),
        ];
    }

    private function fallbackRate(string $method): array
    {
        return [
            'price' => $method === 'express' ? 4500.0 : 2500.0,
            'delivery' => $method === 'express' ? '1–2 days' : '3–5 days',
            'zone' => 'Default',
        ];
    }
}
