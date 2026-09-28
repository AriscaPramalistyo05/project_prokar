<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\PaymentStatusService;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentStatusServiceTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;
    protected Product $product;
    protected Order $order;
    protected PaymentStatusService $paymentStatusService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
        ]);

        // Produk dengan stok 1 unit (contoh barang bekas)
        $this->product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Laptop ThinkPad X1 Second',
            'slug' => 'laptop-thinkpad-x1-second',
            'brand' => 'Lenovo',
            'price' => 5000000,
            'stock' => 1,
            'status' => 'available',
        ]);

        // Simulasikan reservasi stok saat checkout
        app(StockService::class)->reserveStock($this->product->id, 1);
        $this->product->refresh();

        // Stok saat ini 0 dan status 'sold'
        $this->assertEquals(0, $this->product->stock);
        $this->assertEquals('sold', $this->product->status);

        $this->order = Order::create([
            'order_code' => 'ORD-20260927-0099',
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'customer_phone' => '08123456789',
            'subtotal' => 5000000,
            'shipping_cost' => 0,
            'total' => 5000000,
            'status' => 'pending',
            'payment_method' => 'midtrans',
            'payment_status' => 'unpaid',
            'stock_reserved' => true,
        ]);

        OrderItem::create([
            'order_id' => $this->order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_price' => $this->product->price,
            'quantity' => 1,
            'subtotal' => 5000000,
        ]);

        $this->paymentStatusService = app(PaymentStatusService::class);
    }

    public function test_expired_transaction_cancels_order_and_releases_stock(): void
    {
        $payload = [
            'order_id' => $this->order->order_code,
            'transaction_status' => 'expire',
            'status_code' => '202',
            'gross_amount' => '5000000.00',
        ];

        $updatedOrder = $this->paymentStatusService->processTransactionStatus($this->order, $payload);

        // Status order harus menjadi cancelled dan unpaid
        $this->assertEquals('cancelled', $updatedOrder->status);
        $this->assertEquals('unpaid', $updatedOrder->payment_status);
        $this->assertNotNull($updatedOrder->stock_released_at);

        // Stok produk harus kembali ke 1 dan status available
        $this->product->refresh();
        $this->assertEquals(1, $this->product->stock);
        $this->assertEquals('available', $this->product->status);
    }

    public function test_stock_release_is_idempotent_and_never_duplicates(): void
    {
        $payload = [
            'order_id' => $this->order->order_code,
            'transaction_status' => 'expire',
            'status_code' => '202',
            'gross_amount' => '5000000.00',
        ];

        // Panggilan pertama (misal via Webhook)
        $this->paymentStatusService->processTransactionStatus($this->order, $payload);
        $this->product->refresh();
        $this->assertEquals(1, $this->product->stock);

        // Panggilan kedua (misal via GET Status API saat user refresh halaman)
        $this->paymentStatusService->processTransactionStatus($this->order->fresh(), $payload);
        $this->product->refresh();

        // Stok HARUS TETAP 1 (tidak boleh bertambah jadi 2)
        $this->assertEquals(1, $this->product->stock);
        $this->assertEquals('available', $this->product->status);
    }

    public function test_settlement_marks_order_as_paid_and_keeps_stock_sold(): void
    {
        $payload = [
            'order_id' => $this->order->order_code,
            'transaction_status' => 'settlement',
            'status_code' => '200',
            'gross_amount' => '5000000.00',
        ];

        $updatedOrder = $this->paymentStatusService->processTransactionStatus($this->order, $payload);

        $this->assertEquals('processing', $updatedOrder->status);
        $this->assertEquals('paid', $updatedOrder->payment_status);
        $this->assertNotNull($updatedOrder->paid_at);

        // Stok tetap 0 dan status tetap sold
        $this->product->refresh();
        $this->assertEquals(0, $this->product->stock);
        $this->assertEquals('sold', $this->product->status);
    }

    public function test_checkout_success_page_renders_expired_ui(): void
    {
        // Set order ke cancelled
        $this->order->update([
            'status' => 'cancelled',
            'payment_status' => 'unpaid',
            'midtrans_response' => ['transaction_status' => 'expire'],
        ]);

        $response = $this->get(route('checkout.success', $this->order->order_code));

        $response->assertStatus(200);
        $response->assertSee('Batas Waktu Pembayaran Berakhir');
        $response->assertSee('Stok Produk Telah Dipulihkan');
        $response->assertSee('Kedaluwarsa (Expired)');
        $response->assertDontSee('Pembayaran Berhasil');
    }
}
