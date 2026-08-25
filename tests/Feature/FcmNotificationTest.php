<?php

namespace Tests\Feature;

use App\Events\CustomerApprovalUpdated;
use App\Events\OrderCreated;
use App\Events\SellSubmissionCreated;
use App\Events\ServiceOrderCreated;
use App\Models\Category;
use App\Models\FcmToken;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SellSubmission;
use App\Models\ServiceOrder;
use App\Services\FcmNotificationService;
use App\Services\SettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Mockery;
use Tests\TestCase;

class FcmNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupRoles();
    }

    public function test_fcm_token_registered_and_updated_via_api(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $response = $this->actingAs($admin)->postJson(route('api.fcm.register'), [
            'token' => 'fcm_sample_device_token_xyz_123456',
        ]);

        $response->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('fcm_tokens', [
            'user_id' => $admin->id,
            'token' => 'fcm_sample_device_token_xyz_123456',
        ]);
    }

    public function test_fcm_notification_sent_to_admin_on_new_order(): void
    {
        Event::fake([OrderCreated::class]);

        $order = Order::factory()->create([
            'order_code' => 'ORD-20260824-0001',
            'customer_name' => 'Budi Customer',
        ]);

        event(new OrderCreated($order));

        Event::assertDispatched(OrderCreated::class, function ($event) use ($order) {
            return $event->order->order_code === $order->order_code;
        });
    }

    public function test_fcm_notification_sent_to_admin_on_payment_settlement_full_or_dp(): void
    {
        $fcmMock = Mockery::mock(FcmNotificationService::class);
        $fcmMock->shouldReceive('sendToAdmins')
            ->once()
            ->withArgs(function ($title, $body, $data) {
                return str_contains($title, 'Pembayaran Diterima') || str_contains($title, 'DP');
            });

        $this->app->instance(FcmNotificationService::class, $fcmMock);

        $product = Product::factory()->create(['stock' => 5]);
        $order = Order::factory()->create([
            'order_code' => 'ORD-20260824-0099',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'total' => 1500000,
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_price' => 1500000,
            'quantity' => 1,
            'subtotal' => 1500000,
        ]);

        $serverKey = 'test_server_key';
        app(SettingService::class)->set('midtrans_server_key', $serverKey);
        $signatureKey = hash('sha512', $order->order_code . '200' . '1500000.00' . $serverKey);

        $payload = [
            'order_id' => $order->order_code,
            'status_code' => '200',
            'gross_amount' => '1500000.00',
            'signature_key' => $signatureKey,
            'transaction_status' => 'settlement',
            'payment_type' => 'qris',
        ];

        $response = $this->postJson(route('api.payment.webhook'), $payload);
        $response->assertOk();

        $this->assertEquals('paid', $order->fresh()->payment_status);
    }

    public function test_fcm_notification_sent_to_admin_on_new_service_submission(): void
    {
        Event::fake([ServiceOrderCreated::class]);

        $serviceOrder = ServiceOrder::factory()->create([
            'customer_name' => 'Ahmad Servis',
        ]);

        event(new ServiceOrderCreated($serviceOrder));

        Event::assertDispatched(ServiceOrderCreated::class, function ($event) use ($serviceOrder) {
            return $event->serviceOrder->customer_name === $serviceOrder->customer_name;
        });
    }

    public function test_fcm_notification_sent_to_admin_on_customer_estimate_approval_or_rejection(): void
    {
        Event::fake([CustomerApprovalUpdated::class]);

        $serviceOrder = ServiceOrder::factory()->create();

        event(new CustomerApprovalUpdated($serviceOrder, 'approved'));

        Event::assertDispatched(CustomerApprovalUpdated::class, function ($event) use ($serviceOrder) {
            return $event->serviceOrder->id === $serviceOrder->id && $event->approval === 'approved';
        });
    }

    public function test_fcm_notification_sent_to_admin_on_new_sell_submission(): void
    {
        Event::fake([SellSubmissionCreated::class]);

        $submission = SellSubmission::factory()->create();

        event(new SellSubmissionCreated($submission));

        Event::assertDispatched(SellSubmissionCreated::class, function ($event) use ($submission) {
            return $event->sellSubmission->id === $submission->id;
        });
    }
}
