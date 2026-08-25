<?php

namespace Tests\Feature;

use App\Livewire\Admin\ProductForm;
use App\Livewire\Admin\ProductIndex;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_public_product_catalog_loads_successfully(): void
    {
        $category = Category::factory()->create(['name' => 'Mesin Cuci', 'slug' => 'mesin-cuci']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Mesin Cuci Sharp 8KG',
            'status' => 'available',
        ]);

        $response = $this->get(route('produk.index'));
        $response->assertOk();
        $response->assertSee('Mesin Cuci Sharp 8KG');
    }

    public function test_product_detail_page_loads_with_images_and_specifications(): void
    {
        $category = Category::factory()->create(['name' => 'Kulkas']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Kulkas 2 Pintu LG Inverter',
            'brand' => 'LG',
            'model' => 'GN-B195SQMT',
            'status' => 'available',
        ]);

        ProductImage::factory()->create([
            'product_id' => $product->id,
            'is_primary' => true,
        ]);

        $response = $this->get(route('produk.show', $product->slug));
        $response->assertOk();
        $response->assertSee('Kulkas 2 Pintu LG Inverter');
        $response->assertSee('LG');
    }

    public function test_product_filter_by_category(): void
    {
        $cat1 = Category::factory()->create(['name' => 'TV', 'slug' => 'tv']);
        $cat2 = Category::factory()->create(['name' => 'AC', 'slug' => 'ac']);

        $tv = Product::factory()->create(['category_id' => $cat1->id, 'name' => 'Smart TV 43 Inch']);
        $ac = Product::factory()->create(['category_id' => $cat2->id, 'name' => 'AC Split 1 PK']);

        $response = $this->get(route('produk.index', ['kategori' => 'tv']));
        $response->assertOk();
        $response->assertSee('Smart TV 43 Inch');
    }

    public function test_sold_product_remains_accessible_with_canonical_tag(): void
    {
        $product = Product::factory()->sold()->create([
            'name' => 'Televisi Samsung Bekas Terjual',
        ]);

        $response = $this->get(route('produk.show', $product->slug));
        $response->assertOk();
        $response->assertSee('Terjual');
    }

    public function test_admin_can_access_product_index_and_search(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $product = Product::factory()->create(['name' => 'Kulkas Polytron']);

        Livewire::actingAs($admin)
            ->test(ProductIndex::class)
            ->assertSee('Kulkas Polytron')
            ->set('search', 'Kulkas')
            ->assertSee('Kulkas Polytron');
    }

    public function test_admin_can_create_product_via_livewire_form(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $category = Category::factory()->create();

        $image = UploadedFile::fake()->image('test_product.jpg', 800, 800);

        Livewire::actingAs($admin)
            ->test(ProductForm::class)
            ->set('category_id', $category->id)
            ->set('name', 'Microwave Sharp R-21D0')
            ->set('brand', 'Sharp')
            ->set('model', 'R-21D0')
            ->set('price', 850000)
            ->set('stock', 1)
            ->set('weight', 12000)
            ->set('condition_type', 'Seperti Baru')
            ->set('condition_color', 'green')
            ->set('status', 'available')
            ->set('media', [$image])
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Microwave Sharp R-21D0',
            'brand' => 'Sharp',
            'price' => 850000,
        ]);
    }

    public function test_non_admin_cannot_access_admin_product_form(): void
    {
        $this->get(route('admin.products.create'))
            ->assertRedirect(route('login'));

        $customer = $this->actingAsCustomer();
        $this->actingAs($customer)
            ->get(route('admin.products.create'))
            ->assertForbidden();
    }
}
