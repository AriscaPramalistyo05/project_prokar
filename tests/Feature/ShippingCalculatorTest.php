<?php

namespace Tests\Feature;

use App\Services\BiteshipService;
use App\Services\RajaOngkirService;
use Tests\TestCase;

class ShippingCalculatorTest extends TestCase
{
    public function test_rajaongkir_service_provides_cargo_rates(): void
    {
        $service = new RajaOngkirService();
        $rates = $service->getCargoCost('3374', 15000); // 15kg to Semarang

        $this->assertIsArray($rates);
        $this->assertNotEmpty($rates);
        $this->assertArrayHasKey('courier_name', $rates[0]);
        $this->assertArrayHasKey('cost', $rates[0]);
        $this->assertGreaterThan(0, $rates[0]['cost']);
    }

    public function test_biteship_service_provides_cargo_rates(): void
    {
        $service = new BiteshipService();
        $rates = $service->getCargoCost('50268', 25000); // 25kg to postal code 50268

        $this->assertIsArray($rates);
        $this->assertNotEmpty($rates);
        $this->assertArrayHasKey('courier_name', $rates[0]);
        $this->assertArrayHasKey('cost', $rates[0]);
        $this->assertGreaterThan(0, $rates[0]['cost']);
    }

    public function test_cargo_rates_scale_with_higher_weight(): void
    {
        $service = new RajaOngkirService();
        $rate10kg = $service->getCargoCost('3374', 10000);
        $rate40kg = $service->getCargoCost('3374', 40000);

        $this->assertGreaterThan($rate10kg[0]['cost'], $rate40kg[0]['cost']);
    }
}
