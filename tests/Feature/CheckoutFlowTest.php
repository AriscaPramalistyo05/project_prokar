<?php

namespace Tests\Feature;

use App\Mail\OrderConfirmationMail;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Services\CartService;
use App\Services\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock MidtransService getSnapToken agar tidak melakukan HTTP call beneran ke Midtrans
        $mockMidtrans = $this->getMockBuilder(MidtransService::class)
            ->onlyMethods(['getSnapToken'])
            ->getMock();

        $mockMidtrans->method('getSnapToken')->willReturn('mock-snap-token-12345');
        $this->app->instance(MidtransService::class, $mockMidtrans);
    }

    public function test_checkout_address_form_submission_creates_order_and_dispatches_pay_midtrans(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'test-kategori'],
            ['name' => 'Kategori Test']
        );

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'TV Test Checkout',
            'slug' => 'tv-test-checkout',
            'brand' => 'Samsung',
            'price' => 1500000,
            'stock' => 1,
            'status' => 'available',
        ]);

        $cartService = app(CartService::class);
        $cartService->addItem($product->id, 1);

        Livewire::test('frontend.checkout-address-form')
            ->set('name', 'Budi Santoso')
            ->set('province_id', '33')
            ->set('regency_id', '3320') // Jepara (Local area)
            ->set('district_id', '3320050')
            ->set('village_id', '3320050001')
            ->set('postal_code', '59411')
            ->set('address_detail', 'Jl. Pemuda No. 123, Mlonggo')
            ->set('phone', '081234567890')
            ->set('email', 'budi@example.com')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertDispatched('pay-midtrans');

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Budi Santoso',
            'customer_email' => 'budi@example.com',
            'customer_phone' => '081234567890',
            'subtotal' => 1500000,
            'shipping_cost' => 50000,
            'total' => 1550000,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'midtrans_token' => 'mock-snap-token-12345',
        ]);
    }

    public function test_checkout_cargo_shipping_cost_matches_order_total(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'test-kategori-cargo'],
            ['name' => 'Kategori Cargo']
        );

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Mesin Cuci Test Cargo',
            'slug' => 'mesin-cuci-test-cargo',
            'brand' => 'Sharp',
            'price' => 2500000,
            'stock' => 2,
            'status' => 'available',
        ]);

        $cartService = app(CartService::class);
        $cartService->addItem($product->id, 1);

        // Semarang (Luar area lokal - butuh kargo)
        Livewire::test('frontend.checkout-address-form')
            ->set('name', 'Andi Semarang')
            ->set('province_id', '33')
            ->set('regency_id', '3374')
            ->set('district_id', '3374010')
            ->set('village_id', '3374010001')
            ->set('postal_code', '50268')
            ->set('address_detail', 'Jl. Banjarsari Selatan No. 10')
            ->set('phone', '081234567891')
            ->set('email', 'andi@example.com')
            ->set('shippingCost', 57000)
            ->set('shippingCourier', 'JNE - JNE Trucking')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertDispatched('pay-midtrans');

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Andi Semarang',
            'subtotal' => 2500000,
            'shipping_cost' => 57000,
            'total' => 2557000,
            'status' => 'pending',
        ]);
    }

    public function test_webhook_rejects_invalid_signature(): void
    {
        $serverKey = 'test-server-key-123';
        app(\App\Services\SettingService::class)->set('midtrans_server_key', $serverKey);

        $response = $this->postJson(route('api.payment.webhook'), [
            'order_id' => 'ORD-20260817-9999',
            'status_code' => '200',
            'gross_amount' => '1550000.00',
            'signature_key' => 'invalid-signature-key-value',
            'transaction_status' => 'settlement',
        ]);

        $response->assertStatus(400);
        $response->assertJson(['message' => 'Invalid signature key']);
    }

    public function test_webhook_successful_settlement_updates_order_reduces_stock_and_sends_email(): void
    {
        Mail::fake();

        $category = Category::firstOrCreate(
            ['slug' => 'test-kategori-webhook'],
            ['name' => 'Kategori Webhook']
        );

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Kulkas Webhook Test',
            'slug' => 'kulkas-webhook-test',
            'brand' => 'LG',
            'price' => 2000000,
            'stock' => 1,
            'status' => 'available',
        ]);

        $order = Order::create([
            'order_code' => 'ORD-20260817-8888',
            'customer_name' => 'Siti Aminah',
            'customer_email' => 'siti@example.com',
            'customer_phone' => '081987654321',
            'address_detail' => 'Jl. Kartini No. 45',
            'province_id' => '33',
            'regency_id' => '3320',
            'district_id' => '3320050',
            'village_id' => '3320050001',
            'subtotal' => 2000000,
            'shipping_cost' => 50000,
            'total' => 2050000,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $order->orderItems()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_price' => $product->price,
            'quantity' => 1,
            'subtotal' => 2000000,
        ]);

        $serverKey = 'test-server-key-123';
        app(\App\Services\SettingService::class)->set('midtrans_server_key', $serverKey);

        $orderId = $order->order_code;
        $statusCode = '200';
        $grossAmount = '2050000.00';
        $validSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        $response = $this->postJson(route('api.payment.webhook'), [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $validSignature,
            'transaction_status' => 'settlement',
            'payment_type' => 'gopay',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        // Assert Order updated
        $order->refresh();
        $this->assertEquals('processing', $order->status);
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('gopay', $order->payment_method);
        $this->assertNotNull($order->paid_at);

        // Assert Stock reduced and status updated to sold
        $product->refresh();
        $this->assertEquals(0, $product->stock);
        $this->assertEquals('sold', $product->status);

        // Assert Email Sent
        Mail::assertSent(OrderConfirmationMail::class, function ($mail) use ($order) {
            return $mail->hasTo('siti@example.com') && $mail->order->id === $order->id;
        });
    }

    public function test_buy_now_direct_checkout_only_checks_out_selected_product_and_preserves_regular_cart(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'test-direct-checkout'],
            ['name' => 'Kategori Direct']
        );

        $productA = Product::create([
            'category_id' => $category->id,
            'name' => 'Produk A (Keranjang Reguler)',
            'slug' => 'produk-a',
            'brand' => 'Brand A',
            'price' => 500000,
            'stock' => 5,
            'status' => 'available',
        ]);

        $productB = Product::create([
            'category_id' => $category->id,
            'name' => 'Produk B (Beli Sekarang)',
            'slug' => 'produk-b',
            'brand' => 'Brand B',
            'price' => 1200000,
            'stock' => 2,
            'status' => 'available',
        ]);

        $cartService = app(CartService::class);

        // 1. User memasukkan Produk A ke keranjang reguler
        $cartService->addItem($productA->id, 1);
        $this->assertEquals(1, $cartService->count());

        // 2. User melihat Produk B lalu menekan "Beli Sekarang"
        Livewire::test('frontend.add-to-cart-button', ['productId' => $productB->id])
            ->call('buyNow')
            ->assertRedirect(route('checkout.address'));

        // 3. Verifikasi: Halaman checkout HANYA memuat Produk B (Bukan Produk A)
        $checkoutItems = $cartService->getCheckoutItems();
        $this->assertCount(1, $checkoutItems);
        $this->assertEquals($productB->id, $checkoutItems[0]['id']);
        $this->assertEquals(1200000, $checkoutItems[0]['unit_price']);

        // 4. Lakukan Checkout untuk Produk B
        Livewire::test('frontend.checkout-address-form')
            ->set('name', 'Budi Pembeli')
            ->set('deliveryType', 'pickup')
            ->set('paymentOption', 'cash_store')
            ->set('phone', '081234567899')
            ->set('email', 'pembeli@example.com')
            ->call('submit')
            ->assertHasNoErrors();

        // 5. Verifikasi: Order yang dibuat HANYA berisi Produk B
        $order = Order::latest()->first();
        $this->assertEquals(1200000, $order->subtotal);
        $this->assertCount(1, $order->orderItems);
        $this->assertEquals('Produk B (Beli Sekarang)', $order->orderItems[0]->product_name);

        // 6. Verifikasi: Sesi direct checkout sudah dibersihkan, dan Produk A MASIH TERSIMPAN di keranjang reguler
        $this->assertFalse($cartService->isDirectCheckout());
        $remainingRegularItems = $cartService->getItems();
        $this->assertCount(1, $remainingRegularItems);
        $this->assertEquals($productA->id, $remainingRegularItems[0]['id']);
    }

    public function test_sold_out_products_are_auto_pruned_and_not_resurrected_in_cart(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'test-auto-prune'],
            ['name' => 'Kategori Auto Prune']
        );

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Barang Unik Bekas 1 Unit',
            'slug' => 'barang-unik-bekas-1-unit',
            'brand' => 'Sony',
            'price' => 800000,
            'stock' => 1,
            'status' => 'available',
        ]);

        $cartService = app(CartService::class);
        $cartService->addItem($product->id, 1);
        $this->assertEquals(1, $cartService->count());

        // Simulasi barang terjual / status 'sold'
        $product->update(['stock' => 0, 'status' => 'sold']);

        // Saat keranjang diakses kembali, barang yang sold otomatis di-prune (tidak muncul)
        $items = $cartService->getItems();
        $this->assertCount(0, $items);
    }
}
