<?php

namespace App\Services;

use App\Models\Setting;

class PaymentGatewaySettingsService
{
    public function isEnabled(string $gateway): bool
    {
        return Setting::get("payment_{$gateway}_enabled", '0') === '1';
    }

    public function enabledGateways(): array
    {
        $gateways = [];
        foreach (['paystack', 'flutterwave', 'bank_transfer'] as $g) {
            if ($this->isEnabled($g)) {
                $gateways[] = $g;
            }
        }

        return $gateways;
    }

    public function getBankDetails(): array
    {
        return [
            'bank_name'      => Setting::get('bank_transfer_bank_name', ''),
            'account_number' => Setting::get('bank_transfer_account_number', ''),
            'account_name'   => Setting::get('bank_transfer_account_name', ''),
            'instructions'   => Setting::get('bank_transfer_instructions', ''),
        ];
    }
}
