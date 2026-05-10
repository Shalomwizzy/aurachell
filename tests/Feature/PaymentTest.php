<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private string $secret = 'test_paystack_secret';

    protected function setUp(): void
    {
        parent::setUp();
        config(['paystack.secretKey' => $this->secret]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function makeOrder(int $stock = 10): array
    {
        $product = Product::factory()->create(['stock_quantity' => $stock, 'price' => 10000]);

        $order = Order::create([
            'order_number' => 'AUR-TEST-'.uniqid(),
            'payment_reference' => 'ref_'.uniqid(),
            'status' => 'pending',
            'payment_status' => 'pending',
            'subtotal' => 10000,
            'shipping_fee' => 2500,
            'tax' => 0,
            'discount' => 0,
            'total' => 12500,
            'currency' => 'NGN',
            'guest_email' => 'guest@test.com',
            'guest_name' => 'Guest User',
            'shipping_address' => ['address_line_1' => '123 St', 'city' => 'Lagos', 'state' => 'Lagos'],
            'billing_address' => ['address_line_1' => '123 St', 'city' => 'Lagos', 'state' => 'Lagos'],
            'placed_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'quantity' => 2,
            'unit_price' => 10000,
            'total_price' => 20000,
        ]);

        return [$order, $product];
    }

    private function webhookPayload(Order $order): array
    {
        return [
            'event' => 'charge.success',
            'data' => [
                'reference' => $order->payment_reference,
                'amount' => (int) ($order->total * 100),
                'currency' => 'NGN',
                'status' => 'success',
            ],
        ];
    }

    private function postWebhook(array $payload): TestResponse
    {
        $body = json_encode($payload);
        $sig = hash_hmac('sha512', $body, $this->secret);

        return $this->call(
            'POST',
            '/api/webhook/paystack',
            [],
            [],
            [],
            ['HTTP_X_PAYSTACK_SIGNATURE' => $sig, 'CONTENT_TYPE' => 'application/json'],
            $body
        );
    }

    // ── Webhook tests ─────────────────────────────────────────────────────────

    public function test_webhook_rejects_invalid_signature(): void
    {
        $response = $this->call(
            'POST',
            '/api/webhook/paystack',
            [],
            [],
            [],
            ['HTTP_X_PAYSTACK_SIGNATURE' => 'bad_sig', 'CONTENT_TYPE' => 'application/json'],
            json_encode(['event' => 'charge.success', 'data' => []])
        );

        $response->assertStatus(401);
    }

    public function test_webhook_marks_order_paid(): void
    {
        Mail::fake();
        [$order] = $this->makeOrder();

        $this->postWebhook($this->webhookPayload($order))->assertOk();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'paid',
            'status' => 'paid',
        ]);
    }

    public function test_webhook_decrements_product_stock(): void
    {
        Mail::fake();
        [$order, $product] = $this->makeOrder(stock: 10);

        $this->postWebhook($this->webhookPayload($order))->assertOk();

        // OrderItem has quantity=2, so stock should drop from 10 to 8
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8,
        ]);
    }

    public function test_webhook_is_idempotent_on_double_fire(): void
    {
        Mail::fake();
        [$order, $product] = $this->makeOrder(stock: 10);
        $payload = $this->webhookPayload($order);

        $this->postWebhook($payload)->assertOk();
        $this->postWebhook($payload)->assertOk(); // second fire — must be a no-op

        // Stock must only have been decremented once (10 - 2 = 8, not 6)
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8,
        ]);
    }

    public function test_webhook_creates_order_status_history_record(): void
    {
        Mail::fake();
        [$order] = $this->makeOrder();

        $this->postWebhook($this->webhookPayload($order))->assertOk();

        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'status' => 'paid',
        ]);
    }

    public function test_webhook_increments_coupon_on_order_with_coupon(): void
    {
        Mail::fake();
        $coupon = Coupon::factory()->create(['used_count' => 0]);
        [$order, $product] = $this->makeOrder();
        $order->update(['coupon_id' => $coupon->id]);

        $this->postWebhook($this->webhookPayload($order))->assertOk();

        $this->assertSame(1, $coupon->fresh()->used_count);
    }

    // ── Guest order access control ────────────────────────────────────────────

    public function test_guest_cannot_view_order_success_without_session_token(): void
    {
        $order = Order::create([
            'order_number' => 'AUR-2026-TEST01',
            'status' => 'paid',
            'payment_status' => 'paid',
            'subtotal' => 10000,
            'shipping_fee' => 2500,
            'tax' => 0,
            'discount' => 0,
            'total' => 12500,
            'currency' => 'NGN',
            'guest_email' => 'guest@test.com',
            'guest_name' => 'Guest',
            'shipping_address' => ['address_line_1' => '123 St', 'city' => 'Lagos'],
            'billing_address' => ['address_line_1' => '123 St', 'city' => 'Lagos'],
            'placed_at' => now(),
        ]);

        $this->get('/order/success/'.$order->order_number)
            ->assertNotFound();
    }

    public function test_guest_can_view_order_success_with_valid_session_token(): void
    {
        $order = Order::create([
            'order_number' => 'AUR-2026-TEST02',
            'status' => 'paid',
            'payment_status' => 'paid',
            'subtotal' => 10000,
            'shipping_fee' => 2500,
            'tax' => 0,
            'discount' => 0,
            'total' => 12500,
            'currency' => 'NGN',
            'guest_email' => 'guest@test.com',
            'guest_name' => 'Guest',
            'shipping_address' => ['address_line_1' => '123 St', 'city' => 'Lagos'],
            'billing_address' => ['address_line_1' => '123 St', 'city' => 'Lagos'],
            'placed_at' => now(),
        ]);

        $this->withSession(['last_order_number' => $order->order_number])
            ->get('/order/success/'.$order->order_number)
            ->assertOk();
    }

    public function test_authenticated_user_can_view_own_order_success(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_number' => 'AUR-2026-TEST03',
            'user_id' => $user->id,
            'status' => 'paid',
            'payment_status' => 'paid',
            'subtotal' => 10000,
            'shipping_fee' => 2500,
            'tax' => 0,
            'discount' => 0,
            'total' => 12500,
            'currency' => 'NGN',
            'shipping_address' => ['address_line_1' => '123 St', 'city' => 'Lagos'],
            'billing_address' => ['address_line_1' => '123 St', 'city' => 'Lagos'],
            'placed_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/order/success/'.$order->order_number)
            ->assertOk();
    }

    public function test_authenticated_user_cannot_view_another_users_order(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $order = Order::create([
            'order_number' => 'AUR-2026-TEST04',
            'user_id' => $owner->id,
            'status' => 'paid',
            'payment_status' => 'paid',
            'subtotal' => 10000,
            'shipping_fee' => 2500,
            'tax' => 0,
            'discount' => 0,
            'total' => 12500,
            'currency' => 'NGN',
            'shipping_address' => ['address_line_1' => '123 St', 'city' => 'Lagos'],
            'billing_address' => ['address_line_1' => '123 St', 'city' => 'Lagos'],
            'placed_at' => now(),
        ]);

        $this->actingAs($other)
            ->get('/order/success/'.$order->order_number)
            ->assertNotFound();
    }
}
