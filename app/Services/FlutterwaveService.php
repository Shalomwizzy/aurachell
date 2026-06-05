<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveService
{
    private string $secretKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('flutterwave.secret_key', '');
        $this->baseUrl   = config('flutterwave.base_url', 'https://api.flutterwave.com/v3');
    }

    public function initializePayment(array $payload): string
    {
        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/payments", $payload);

        if (! $response->successful() || $response->json('status') !== 'success') {
            Log::error('Flutterwave init failed', ['body' => $response->body()]);
            throw new \RuntimeException('Could not initialise Flutterwave payment. Please try again.');
        }

        return $response->json('data.link');
    }

    public function verifyTransaction(string $transactionId): array
    {
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/transactions/{$transactionId}/verify");

        if (! $response->successful()) {
            Log::error('Flutterwave verify failed', ['id' => $transactionId, 'body' => $response->body()]);
            throw new \RuntimeException('Payment verification failed.');
        }

        return $response->json('data', []);
    }

    public function validateWebhookSignature(string $payload, string $signature): bool
    {
        $hash = hash_hmac('sha256', $payload, config('flutterwave.webhook_hash', ''));

        return hash_equals($hash, $signature);
    }
}
