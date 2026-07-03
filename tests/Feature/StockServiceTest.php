<?php

namespace Tests\Feature;

use App\Exceptions\ProductUnavailableException;
use App\Models\Category;
use App\Models\Product;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockServiceTest extends TestCase
{
    use RefreshDatabase;

    protected StockService $stockService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stockService = new StockService();
    }

    private function createProduct(array $overrides = []): Product
    {
        $category = Category::create(['name' => 'Test', 'slug' => 'test']);

        return Product::create(array_merge([
            'category_id' => $category->id,
            'name' => 'Kulkas Test',
            'slug' => 'kulkas-test',
            'brand' => 'TestBrand',
            'model' => 'TestModel',
            'price' => 1000000,
            'stock' => 1,
            'status' => 'available',
        ], $overrides));
    }

    public function test_reserve_stock_decrements_and_marks_sold(): void
    {
        $product = $this->createProduct(['stock' => 1]);

        $updated = $this->stockService->reserveStock($product->id);

        $this->assertEquals(0, $updated->stock);
        $this->assertEquals('sold', $updated->status);
    }

    public function test_reserve_stock_decrements_without_sold_when_stock_remaining(): void
    {
        $product = $this->createProduct(['stock' => 3]);

        $updated = $this->stockService->reserveStock($product->id, 2);

        $this->assertEquals(1, $updated->stock);
        $this->assertEquals('available', $updated->status);
    }

    public function test_reserve_stock_throws_when_product_sold(): void
    {
        $product = $this->createProduct(['stock' => 0, 'status' => 'sold']);

        $this->expectException(ProductUnavailableException::class);
        $this->stockService->reserveStock($product->id);
    }

    public function test_reserve_stock_throws_when_insufficient_stock(): void
    {
        $product = $this->createProduct(['stock' => 1]);

        $this->expectException(ProductUnavailableException::class);
        $this->stockService->reserveStock($product->id, 5);
    }

    public function test_release_stock_increments_and_restores_available(): void
    {
        $product = $this->createProduct(['stock' => 0, 'status' => 'sold']);

        $updated = $this->stockService->releaseStock($product->id);

        $this->assertEquals(1, $updated->stock);
        $this->assertEquals('available', $updated->status);
    }

    public function test_release_stock_increments_without_status_change(): void
    {
        $product = $this->createProduct(['stock' => 2, 'status' => 'available']);

        $updated = $this->stockService->releaseStock($product->id, 3);

        $this->assertEquals(5, $updated->stock);
        $this->assertEquals('available', $updated->status);
    }
}
