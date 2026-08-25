<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\TestCase;

class ProductWeightTest extends TestCase
{
    public function test_product_chargeable_weight_returns_physical_weight_in_grams(): void
    {
        $product = new Product([
            'name' => 'Kulkas Sharp 2 Pintu',
            'weight' => 25000, // 25 kg
        ]);

        $this->assertEquals(25000, $product->getChargeableWeightGram());
    }

    public function test_product_minimum_weight_fallback_is_1000_grams(): void
    {
        // 1. Berat 0 / null
        $productNoWeight = new Product([
            'name' => 'Remote TV Universal',
            'weight' => null,
        ]);
        $this->assertEquals(1000, $productNoWeight->getChargeableWeightGram());

        // 2. Berat di bawah 1000 gram (cth: 450 gram)
        $productLight = new Product([
            'name' => 'Kabel Power Audio',
            'weight' => 450,
        ]);
        $this->assertEquals(1000, $productLight->getChargeableWeightGram());
    }
}
