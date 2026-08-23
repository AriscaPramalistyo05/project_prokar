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
}
