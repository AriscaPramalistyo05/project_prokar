<?php

namespace Tests\Feature;

use App\Events\ServiceOrderCreated;
use App\Livewire\Frontend\TrackService;
use App\Mail\OrderConfirmationMail;
use App\Mail\ServiceConfirmationMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Services\SettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class NotificationMailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupRoles();
    }

    public function test_order_invoice_email_sent_to_customer_after_payment(): void
    {
        Mail::fake();

        $product = Product::factory()->create(['stock' => 5]);
        $order = Order::factory()->create([
            'order_code' => 'ORD-20260824-0555',
            'customer_email' => 'customer.pembeli@example.com',
            'customer_name' => 'Pembeli Terverifikasi',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'total' => 2000000,
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_price' => 2000000,
            'quantity' => 1,
            'subtotal' => 2000000,
        ]);

        $serverKey = 'mail_test_server_key';
        app(SettingService::class)->set('midtrans_server_key', $serverKey);
        $signatureKey = hash('sha512', $order->order_code . '200' . '2000000.00' . $serverKey);

        $payload = [
            'order_id' => $order->order_code,
            'status_code' => '200',
            'gross_amount' => '2000000.00',
            'signature_key' => $signatureKey,
            'transaction_status' => 'settlement',
            'payment_type' => 'bank_transfer',
        ];

        $response = $this->postJson(route('api.payment.webhook'), $payload);
        $response->assertOk();

        Mail::assertSent(OrderConfirmationMail::class, function ($mail) use ($order) {
            return $mail->hasTo('customer.pembeli@example.com') &&
                $mail->order->order_code === $order->order_code;
        });
    }

    public function test_service_ticket_email_sent_to_customer_with_tracking_code(): void
    {
        Mail::fake();

        $category = \App\Models\Category::factory()->create();

        Livewire::test(\App\Livewire\Frontend\ServiceForm::class)
            ->set('nama', 'Klien Servis')
            ->set('email', 'service.client@example.com')
            ->set('whatsapp', '081234567890')
            ->set('kategori', $category->id)
            ->set('merek', 'Panasonic Eco Inverter')
            ->set('deskripsi', 'Kulkas tidak dingin sama sekali dan berisik')
            ->set('serviceType', 'kirim')
            ->call('submit');

        Mail::assertSent(ServiceConfirmationMail::class, function ($mail) {
            return $mail->hasTo('service.client@example.com');
        });
    }

    public function test_customer_can_track_live_service_status_updates(): void
    {
        $serviceOrder = ServiceOrder::factory()->create([
            'status' => 'in_progress',
            'device_brand' => 'Mesin Cuci Electrolux',
            'complaint' => 'Sensor putaran motor error',
        ]);

        Livewire::test(TrackService::class, ['code' => $serviceOrder->service_code])
            ->assertSee($serviceOrder->service_code)
            ->assertSee('Pengerjaan');
    }
}
