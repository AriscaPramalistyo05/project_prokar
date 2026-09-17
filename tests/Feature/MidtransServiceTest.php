<?php

namespace Tests\Feature;

use App\Services\MidtransService;
use App\Services\SettingService;
use Midtrans\Config;
use Tests\TestCase;

class MidtransServiceTest extends TestCase
{
    public function test_midtrans_service_config_initialization(): void
    {
        $mockSettingService = $this->createMock(SettingService::class);
        $mockSettingService->method('get')->willReturnCallback(function (string $key, bool $decrypt = false) {
            return match ($key) {
                'midtrans_server_key' => 'SB-Mid-server-test-key',
                'midtrans_client_key' => 'SB-Mid-client-test-key',
                'midtrans_is_production' => '0',
                default => null,
            };
        });

        $this->app->instance(SettingService::class, $mockSettingService);

        $service = new MidtransService();
        $service->initConfig();

        $this->assertEquals('SB-Mid-server-test-key', Config::$serverKey);
        $this->assertEquals('SB-Mid-client-test-key', Config::$clientKey);
        $this->assertFalse(Config::$isProduction);
        $this->assertTrue(Config::$isSanitized);
        $this->assertTrue(Config::$is3ds);
    }

    public function test_verify_signature_key(): void
    {
        $mockSettingService = $this->createMock(SettingService::class);
        $mockSettingService->method('get')->willReturnCallback(function (string $key, bool $decrypt = false) {
            return match ($key) {
                'midtrans_server_key' => 'my-secret-server-key',
                default => null,
            };
        });

        $this->app->instance(SettingService::class, $mockSettingService);

        $service = new MidtransService();

        $orderId = 'ORD-20260817-0001';
        $statusCode = '200';
        $grossAmount = '150000.00';
        $serverKey = 'my-secret-server-key';

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        $this->assertTrue($service->verifySignatureKey($orderId, $statusCode, $grossAmount, $expectedSignature));
        $this->assertFalse($service->verifySignatureKey($orderId, $statusCode, $grossAmount, 'invalid-signature'));
    }

    public function test_payment_instructions_extraction(): void
    {
        $service = new MidtransService();

        // 1. Virtual Account
        $vaData = $service->getPaymentInstructions('bank_transfer', [
            'payment_type' => 'bank_transfer',
            'va_numbers' => [['bank' => 'bri', 'va_number' => '80777089525105080']],
            'expiry_time' => '2026-09-16 14:51:00',
        ]);
        $this->assertEquals('va', $vaData['type']);
        $this->assertEquals('BRI', $vaData['bank']);
        $this->assertEquals('80777089525105080', $vaData['va_number']);
        $this->assertEquals('2026-09-16 14:51:00', $vaData['expiry_time']);

        // 2. Mandiri Bill Payment
        $mandiriData = $service->getPaymentInstructions('echannel', [
            'payment_type' => 'echannel',
            'biller_code' => '70012',
            'bill_key' => '99281928192',
        ]);
        $this->assertEquals('mandiri', $mandiriData['type']);
        $this->assertEquals('70012', $mandiriData['biller_code']);
        $this->assertEquals('99281928192', $mandiriData['bill_key']);

        // 3. QRIS
        $qrisData = $service->getPaymentInstructions('qris', [
            'payment_type' => 'qris',
            'actions' => [
                ['name' => 'generate-qr-code', 'url' => 'https://api.sandbox.midtrans.com/v2/qris/123/qr-code'],
            ],
        ]);
        $this->assertEquals('qris', $qrisData['type']);
        $this->assertEquals('https://api.sandbox.midtrans.com/v2/qris/123/qr-code', $qrisData['qr_url']);

        // 4. Cash Store
        $cashData = $service->getPaymentInstructions('cash_store');
        $this->assertEquals('cash_store', $cashData['type']);
    }
}
