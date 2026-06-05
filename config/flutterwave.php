<?php

return [
    'public_key'   => env('FLUTTERWAVE_PUBLIC_KEY'),
    'secret_key'   => env('FLUTTERWAVE_SECRET_KEY'),
    'webhook_hash' => env('FLUTTERWAVE_WEBHOOK_HASH'),
    'base_url'     => 'https://api.flutterwave.com/v3',
];
