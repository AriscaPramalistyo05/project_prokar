<?php

namespace Tests\Feature;

use App\Livewire\Admin\ProductForm;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_can_create_product_with_images_and_redirects_with_indicator()
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        $category = Category::firstOrCreate(['name' => 'Kulkas'], ['slug' => 'kulkas']);

        $image1 = UploadedFile::fake()->image('kulkas_front.jpg', 600, 600);
        $image2 = UploadedFile::fake()->image('kulkas_inside.jpg', 600, 600);

        Livewire::actingAs($user)
            ->test(ProductForm::class)
            ->set('category_id', $category->id)
            ->set('name', 'Kulkas Polytron 1 Pintu Belleza')
            ->set('brand', 'Polytron')
            ->set('model', 'PRA-18MO')
            ->set('price', 1450000)
            ->set('stock', 2)
            ->set('weight', 25000)
            ->set('condition_type', 'Seperti Baru')
            ->set('condition_color', 'green')
            ->set('status', 'available')
            ->set('media', [$image1, $image2])
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('message')
            ->assertSessionHas('success_title');

        $product = Product::where('name', 'Kulkas Polytron 1 Pintu Belleza')->first();
        $this->assertNotNull($product);
        $this->assertEquals(2, $product->productImages()->count());
        $this->assertTrue($product->productImages()->where('is_primary', true)->exists());
    }

    public function test_can_edit_existing_product_and_replace_or_delete_photo()
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        $product = Product::first();
        $this->assertNotNull($product);

        $initialCount = $product->productImages()->count();
        $firstImage = $product->productImages()->first();

        $newImage = UploadedFile::fake()->image('replaced_photo.jpg', 600, 600);

        Livewire::actingAs($user)
            ->test(ProductForm::class, ['product' => $product])
            ->set('replacingPhoto.' . $firstImage->id, $newImage)
            ->assertDispatched('mary-toast');

        $this->assertEquals($initialCount, $product->fresh()->productImages()->count());
    }
}
