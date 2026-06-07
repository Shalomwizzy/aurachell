<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Unicodeveloper\Paystack\Facades\Paystack;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    // Authenticated user whose cart is stored by user_id (survives across requests).
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name'            => $this->user->name,
            'email'           => $this->user->email,
            'phone'           => '08012345678',
            'address_line_1'  => '123 Test Street',
            'city'            => 'Lagos',
            'state'           => 'Lagos',
            'country'         => 'Nigeria',
            'shipping_method' => 'standard',
            'payment_method'  => 'paystack',
        ], $overrides);
    }

    public function test_checkout_page_redirects_when_cart_is_empty(): void
    {
        $this->get('/checkout')
            ->assertRedirect(route('cart'));
    }

    public function test_checkout_rejects_product_with_zero_stock(): void
    {
        $product = Product::factory()->outOfStock()->create();
        $this->postJson('/cart/add', ['product_id' => $product->id, 'quantity' => 1]);

        $response = $this->post('/checkout/place', $this->payload());

        $response->assertRedirect(route('cart'));
        $response->assertSessionHas('error');
        $this->assertStringContainsString('stock', $response->getSession()->get('error'));
    }

    public function test_checkout_rejects_out_of_stock_variant(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Small',
            'sku' => 'SKU-S-'.uniqid(),
            'price' => $product->price,
            'stock_quantity' => 0,
        ]);

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $response = $this->post('/checkout/place', $this->payload());

        $response->assertRedirect(route('cart'));
        $response->assertSessionHas('error');
        $this->assertStringContainsString('stock', $response->getSession()->get('error'));
    }

    public function test_coupon_used_count_not_incremented_before_payment(): void
    {
        Paystack::shouldReceive('getAuthorizationUrl')
            ->andReturn((object) ['url' => 'https://pay.paystack.co/test']);

        $product = Product::factory()->create(['stock_quantity' => 5, 'price' => 20000]);
        $coupon = Coupon::factory()->create(['code' => 'SAVE10', 'used_count' => 0]);

        $this->postJson('/cart/add', ['product_id' => $product->id, 'quantity' => 1]);
        $this->post('/checkout/place', $this->payload(['coupon_code' => 'SAVE10']));

        // used_count must remain 0 — only incremented after payment is confirmed
        $this->assertSame(0, $coupon->fresh()->used_count);
    }

    public function test_order_is_created_with_pending_status_on_checkout(): void
    {
        Paystack::shouldReceive('getAuthorizationUrl')
            ->andReturn((object) ['url' => 'https://pay.paystack.co/test']);

        $product = Product::factory()->create(['stock_quantity' => 5, 'price' => 15000]);
        $this->postJson('/cart/add', ['product_id' => $product->id, 'quantity' => 1]);
        $this->post('/checkout/place', $this->payload());

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);
    }

    public function test_stock_is_not_decremented_at_order_creation(): void
    {
        Paystack::shouldReceive('getAuthorizationUrl')
            ->andReturn((object) ['url' => 'https://pay.paystack.co/test']);

        $product = Product::factory()->create(['stock_quantity' => 5, 'price' => 15000]);
        $this->postJson('/cart/add', ['product_id' => $product->id, 'quantity' => 2]);
        $this->post('/checkout/place', $this->payload());

        // Stock must not be touched until payment is confirmed
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 5,
        ]);
    }

    public function test_checkout_redirects_to_paystack_on_success(): void
    {
        Paystack::shouldReceive('getAuthorizationUrl')
            ->andReturn((object) ['url' => 'https://pay.paystack.co/test']);

        $product = Product::factory()->create(['stock_quantity' => 5, 'price' => 15000]);
        $this->postJson('/cart/add', ['product_id' => $product->id, 'quantity' => 1]);

        $this->post('/checkout/place', $this->payload())
            ->assertRedirect('https://pay.paystack.co/test');
    }
}
