<?php

namespace Tests\Feature;

use App\Livewire\Admin\SellSubmissionDetail;
use App\Livewire\Frontend\SellForm;
use App\Models\Category;
use App\Models\Product;
use App\Models\SellSubmission;
use App\Models\SellSubmissionImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SellSubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        RateLimiter::clear('submit-sell:' . request()->ip());
        $this->setupRoles();
    }

    public function test_customer_can_submit_sell_form_with_photos(): void
    {
        RateLimiter::clear('submit-sell:' . request()->ip());

        $category = Category::factory()->create(['name' => 'Mesin Cuci', 'slug' => 'mesin-cuci']);
        $photo = UploadedFile::fake()->image('mesin_cuci.jpg', 600, 600);

        Livewire::test(SellForm::class)
            ->set('nama', 'Joko Widodo')
            ->set('whatsapp', '081234567890')
            ->set('province_id', '33')
            ->set('regency_id', '3320')
            ->set('district_id', '3320010')
            ->set('village_id', '3320010001')
            ->set('address_detail', 'Jl. Kolonel Sugiono No. 88')
            ->set('kategori', $category->id)
            ->set('merek', 'LG Smart Inverter 10KG')
            ->set('kondisi', 'baik')
            ->set('deskripsi', 'Kondisi normal siap pakai, mau ganti kapasitas lebih besar.')
            ->set('media', [$photo])
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('sell_submissions', [
            'customer_name' => 'Joko Widodo',
            'device_brand' => 'LG Smart Inverter 10KG',
            'condition' => 'good',
            'status' => 'pending',
        ]);
    }

    public function test_sell_submission_code_generated_format_sell_yyyymmdd_xxxx(): void
    {
        $category = Category::factory()->create();

        $submission = SellSubmission::factory()->create([
            'category_id' => $category->id,
        ]);

        $this->assertMatchesRegularExpression('/^SELL-\d{8}-\d{4}$/', $submission->submission_code);
    }

    public function test_sell_submission_validates_required_device_fields(): void
    {
        RateLimiter::clear('submit-sell:' . request()->ip());

        Livewire::test(SellForm::class)
            ->set('whatsapp', '')
            ->set('merek', '')
            ->set('kondisi', '')
            ->set('kategori', '')
            ->call('submit')
            ->assertHasErrors(['whatsapp', 'merek', 'kondisi', 'kategori']);
    }

    public function test_admin_can_review_and_approve_sell_submission(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $submission = SellSubmission::factory()->create([
            'status' => 'pending',
            'offered_price' => 1500000,
        ]);

        Livewire::actingAs($admin)
            ->test(SellSubmissionDetail::class, ['sellSubmission' => $submission])
            ->set('agreed_price', 1400000)
            ->call('saveAgreedPrice');

        $this->assertEquals('accepted', $submission->fresh()->status);
        $this->assertEquals(1400000, $submission->fresh()->agreed_price);
    }

    public function test_approved_sell_submission_can_be_converted_to_product_catalog(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $category = Category::factory()->create();

        $submission = SellSubmission::factory()->create([
            'category_id' => $category->id,
            'device_brand' => 'Samsung',
            'device_model' => 'RT22FARBDSA',
            'agreed_price' => 1800000,
            'status' => 'accepted',
        ]);

        SellSubmissionImage::create([
            'sell_submission_id' => $submission->id,
            'path' => 'sell_submissions/sample-photo.jpg',
            'type' => 'photo',
        ]);

        Livewire::actingAs($admin)
            ->test(SellSubmissionDetail::class, ['sellSubmission' => $submission])
            ->call('convertToProduct')
            ->assertRedirect();

        $this->assertNotNull($submission->fresh()->converted_product_id);
        $this->assertDatabaseHas('products', [
            'id' => $submission->fresh()->converted_product_id,
            'brand' => 'Samsung',
            'model' => 'RT22FARBDSA',
            'price' => 1800000,
        ]);
    }

    public function test_already_converted_submission_cannot_be_converted_twice(): void
    {
        $admin = $this->actingAsSuperAdmin();
        $product = Product::factory()->create();

        $submission = SellSubmission::factory()->create([
            'converted_product_id' => $product->id,
            'status' => 'ready_for_sale',
        ]);

        $initialProductCount = Product::count();

        Livewire::actingAs($admin)
            ->test(SellSubmissionDetail::class, ['sellSubmission' => $submission])
            ->call('convertToProduct')
            ->assertRedirect(route('admin.products.edit', $product->id));

        $this->assertEquals($initialProductCount, Product::count());
    }
}
