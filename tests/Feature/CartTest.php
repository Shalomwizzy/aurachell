<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    // Single-request tests work for guests (no cross-request session needed)

    public function test_can_add_product_to_cart(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);

        $this->postJson('/cart/add', ['product_id' => $product->id, 'quantity' => 2])
            ->assertOk()
            ->assertJson(['success' => true, 'count' => 2]);
    }

    public function test_cart_rejects_nonexistent_product(): void
    {
        $this->postJson('/cart/add', ['product_id' => 999999, 'quantity' => 1])
            ->assertStatus(422);
    }

    public function test_can_add_product_with_scent_note(): void
    {
        $product = Product::factory()->create();

        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
            'scent_note' => 'Vanilla',
        ])->assertOk()->assertJson(['success' => true]);
    }

    // Multi-request tests use authenticated users — their carts are stored by
    // user_id in the database, so they survive across test-client requests.

    public function test_cart_data_returns_added_items(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create(['price' => 10000]);
        $this->postJson('/cart/add', ['product_id' => $product->id, 'quantity' => 2]);

        $this->getJson('/cart/data')
            ->assertOk()
            ->assertJsonPath('count', 2)
            ->assertJsonFragment(['name' => $product->name]);
    }

    public function test_removing_item_clears_it_from_cart(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();
        $this->postJson('/cart/add', ['product_id' => $product->id, 'quantity' => 1]);

        $itemId = $this->getJson('/cart/data')->json('items.0.id');

        $this->postJson('/cart/remove', ['item_id' => $itemId])
            ->assertOk()
            ->assertJsonPath('count', 0);
    }

    public function test_updating_quantity_reflects_in_cart_data(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()->create();
        $this->postJson('/cart/add', ['product_id' => $product->id, 'quantity' => 1]);

        $itemId = $this->getJson('/cart/data')->json('items.0.id');
        $this->postJson('/cart/update', ['item_id' => $itemId, 'quantity' => 3]);

        $this->getJson('/cart/data')->assertJsonPath('count', 3);
    }
}
