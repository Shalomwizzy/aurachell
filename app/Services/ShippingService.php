<?php

namespace App\Services;

use App\Models\ShippingZone;

class ShippingService
{
    public function getRatesForState(string $state, float $subtotal): array
    {
        $zone = ShippingZone::forState($state);

        if (! $zone) {
            return $this->fallback($subtotal);
        }

        $zone->load('rates');
        $result = [];

        foreach ($zone->rates as $rate) {
            $result[$rate->method] = [
                'price' => $rate->effectivePrice($subtotal),
                'original' => (float) $rate->price,
                'free' => $rate->isFree($subtotal),
                'threshold' => (float) $rate->free_shipping_threshold,
                'delivery' => $rate->deliveryLabel(),
                'zone' => $zone->name,
            ];
        }

        // Guarantee both methods always present
        foreach (['standard', 'express'] as $method) {
            if (! isset($result[$method])) {
                $result[$method] = $this->fallbackRate($method, $subtotal);
            }
        }

        return $result;
    }

    public function calculate(string $state, float $subtotal, string $method): float
    {
        $rates = $this->getRatesForState($state, $subtotal);

        return $rates[$method]['price'] ?? $this->fallbackRate($method, $subtotal)['price'];
    }

    private function fallback(float $subtotal): array
    {
        return [
            'standard' => $this->fallbackRate('standard', $subtotal),
            'express' => $this->fallbackRate('express', $subtotal),
        ];
    }

    private function fallbackRate(string $method, float $subtotal): array
    {
        $price = $method === 'express' ? 4500.0 : 2500.0;
        $threshold = 50000.0;
        $free = $subtotal >= $threshold;

        return [
            'price' => $free ? 0.0 : $price,
            'original' => $price,
            'free' => $free,
            'threshold' => $threshold,
            'delivery' => $method === 'express' ? '1–2 days' : '3–5 days',
            'zone' => 'Default',
        ];
    }
}
