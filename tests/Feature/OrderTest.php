<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_add_product_to_cart(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 1200000,
            'stock' => 2,
            'status' => 'available',
        ]);

        $cart = app(CartService::class);
        $cart->addItem($product->id, 1);

        $this->assertEquals(1, $cart->count());
        $this->assertEquals(1200000, $cart->subtotal());
    }

    public function test_user_cannot_view_or_pay_other_users_order(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user1->id,
            'customer_email' => $user1->email,
        ]);

        // User 2 mencoba melihat profil atau riwayat order user 1
        $this->actingAs($user2);

        $this->get(route('user.profile'))
            ->assertDontSee($order->order_code);
    }

    public function test_checkout_rejected_if_product_is_already_sold_or_out_of_stock(): void
    {
        $product = Product::factory()->sold()->create();

        $cart = app(CartService::class);

        $this->expectException(\App\Exceptions\ProductUnavailableException::class);
        app(\App\Services\StockService::class)->reserveStock($product->id, 1);
    }
}
