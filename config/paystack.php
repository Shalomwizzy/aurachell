<?php

/*
 * Configuration for Paystack — read by unicodeveloper/laravel-paystack package.
 * Values mirror config/services.php so a single .env source of truth still works.
 */

return [
    'publicKey' => env('PAYSTACK_PUBLIC_KEY'),
    'secretKey' => env('PAYSTACK_SECRET_KEY'),
    'paymentUrl' => env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co'),
    'merchantEmail' => env('MERCHANT_EMAIL'),
];
