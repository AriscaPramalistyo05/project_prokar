<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\MarketingKitService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingKitTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'Kulkas & Freezer',
            'slug' => 'kulkas-freezer',
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Kulkas Sharp 2 Pintu Inverter',
            'slug' => 'kulkas-sharp-2-pintu-inverter',
            'brand' => 'Sharp',
            'model' => 'SJ-236ND',
            'description' => 'Kulkas hemat listrik kondisi 95% mulus.',
            'condition_notes' => 'Dingin normal beku cepat, kelistrikan stabil.',
            'condition' => 'Sangat Baik',
            'condition_color' => 'green',
            'price' => 2500000,
            'promo_price' => 2199000,
            'is_promo' => true,
            'stock' => 1,
            'status' => 'available',
        ]);
    }

    public function test_marketing_kit_service_generates_correct_copywriting_templates()
    {
        $service = app(MarketingKitService::class);

        // 1. Template Standard
        $standardText = $service->generateCopywriting($this->product, 'standard');
        $this->assertStringContainsString('Kulkas Sharp 2 Pintu Inverter', $standardText);
        $this->assertStringContainsString('Rp 2.199.000', $standardText);
        $this->assertStringContainsString('https://wa.me/', $standardText);

        // 2. Template Promo / Flash Sale
        $promoText = $service->generateCopywriting($this->product, 'promo');
        $this->assertStringContainsString('FLASH SALE', $promoText);
        $this->assertStringContainsString('Rp 2.199.000', $promoText);
        $this->assertStringContainsString('Rp 2.500.000', $promoText);

        // 3. Template Technical / Spec
        $specText = $service->generateCopywriting($this->product, 'technical');
        $this->assertStringContainsString('SPESIFIKASI', $specText);
        $this->assertStringContainsString('SJ-236ND', $specText);
        $this->assertStringContainsString('Dingin normal beku cepat', $specText);
        $this->assertStringContainsString('Sharp', $specText);
        $this->assertStringContainsString('Kulkas hemat listrik kondisi 95% mulus', $specText);
    }

    public function test_flash_sale_only_active_when_promo_toggle_on_and_promo_price_filled()
    {
        $service = app(MarketingKitService::class);

        // Case 1: Promo ON and promo price filled -> has_promo is true
        $data1 = $service->getMarketingKitData($this->product);
        $this->assertTrue($data1['has_promo']);
        $this->assertNotEmpty($data1['copywriting']['promo']);

        // Case 2: Promo OFF but promo price filled -> has_promo is false
        $this->product->update(['is_promo' => false]);
        $data2 = $service->getMarketingKitData($this->product->fresh());
        $this->assertFalse($data2['has_promo']);
        $this->assertEmpty($data2['copywriting']['promo']);

        // Case 3: Promo ON but promo price is null or 0 -> has_promo is false
        $this->product->update(['is_promo' => true, 'promo_price' => null]);
        $data3 = $service->getMarketingKitData($this->product->fresh());
        $this->assertFalse($data3['has_promo']);
        $this->assertEmpty($data3['copywriting']['promo']);

        $this->product->update(['is_promo' => true, 'promo_price' => 0]);
        $data4 = $service->getMarketingKitData($this->product->fresh());
        $this->assertFalse($data4['has_promo']);
        $this->assertEmpty($data4['copywriting']['promo']);
    }

    public function test_spesifikasi_strictly_matches_product_specifications()
    {
        $service = app(MarketingKitService::class);

        // Test with full specs
        $this->product->update([
            'weight' => 25000,
            'length' => 60,
            'width' => 55,
            'height' => 140,
            'description' => 'Kapasitas 205 liter, tempered glass tray.',
        ]);
        $specText = $service->generateCopywriting($this->product->fresh(), 'technical');

        $this->assertStringContainsString('25 kg', $specText);
        $this->assertStringContainsString('60 × 55 × 140 cm', $specText);
        $this->assertStringContainsString('Kapasitas 205 liter, tempered glass tray', $specText);
        $this->assertStringContainsString('SJ-236ND', $specText);
        $this->assertStringContainsString('Sharp', $specText);

        // Test with empty optional specs: should NOT show fake text
        $emptyProduct = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Kipas Angin Meja',
            'slug' => 'kipas-angin-meja',
            'brand' => 'Maspion',
            'model' => null,
            'description' => null,
            'condition_notes' => null,
            'condition' => 'Baik',
            'price' => 150000,
            'promo_price' => null,
            'is_promo' => false,
            'weight' => 0,
            'stock' => 2,
            'status' => 'available',
        ]);

        $emptySpecText = $service->generateCopywriting($emptyProduct, 'technical');
        $this->assertStringNotContainsString('Model / Tipe:', $emptySpecText);
        $this->assertStringNotContainsString('Catatan Kondisi:', $emptySpecText);
        $this->assertStringNotContainsString('Dimensi', $emptySpecText);
        $this->assertStringNotContainsString('Deskripsi & Spesifikasi Lengkap:', $emptySpecText);
        $this->assertStringContainsString('Maspion', $emptySpecText);
        $this->assertStringContainsString('Kipas Angin Meja', $emptySpecText);
    }

    public function test_marketing_kit_service_generates_valid_share_urls()
    {
        $service = app(MarketingKitService::class);

        // WhatsApp Share URL
        $waUrl = $service->getWhatsAppShareUrl($this->product);
        $this->assertStringStartsWith('https://api.whatsapp.com/send?text=', $waUrl);
        $this->assertStringContainsString(rawurlencode($this->product->name), $waUrl);

        // WhatsApp Direct Order URL
        $waDirect = $service->getWhatsAppDirectOrderUrl($this->product);
        $this->assertStringStartsWith('https://wa.me/', $waDirect);

        // Facebook Share URL
        $fbUrl = $service->getFacebookShareUrl($this->product);
        $this->assertStringStartsWith('https://www.facebook.com/sharer/sharer.php?u=', $fbUrl);

        // Telegram Share URL
        $tgUrl = $service->getTelegramShareUrl($this->product);
        $this->assertStringStartsWith('https://t.me/share/url?', $tgUrl);
    }

    public function test_product_detail_page_renders_opengraph_meta_and_1click_share_widget()
    {
        $response = $this->get('/produk/' . $this->product->slug);

        $response->assertStatus(200);
        $response->assertSeeText('Bagikan & Rekomendasikan');
        $response->assertSeeText('WhatsApp');
        $response->assertSeeText('Facebook');
        $response->assertSeeText('Telegram');
        $response->assertSeeText('Salin Link');

        // Check OpenGraph Tags
        $response->assertSee('property="og:title"', false);
        $response->assertSee('property="og:image"', false);
        $response->assertSee('property="og:description"', false);
    }

    public function test_admin_product_index_can_open_marketing_modal()
    {
        $superAdmin = User::factory()->create([
            'email' => 'admin@prokar.test',
        ]);
        
        // Assign role super_admin if Spatie permission is configured
        if (method_exists($superAdmin, 'assignRole')) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super_admin']);
            $superAdmin->assignRole('super_admin');
        }

        \Livewire\Livewire::actingAs($superAdmin)
            ->test(\App\Livewire\Admin\ProductIndex::class)
            ->assertStatus(200)
            ->call('openMarketingModal', $this->product->id)
            ->assertSet('showMarketingModal', true)
            ->assertSet('selectedMarketingProduct.id', $this->product->id)
            ->assertSee('Teks WA', false)
            ->assertSee('Bagikan & Media', false)
            ->assertSee('Kulkas Sharp 2 Pintu Inverter', false)
            ->assertSee('Standar', false)
            ->assertSee('Flash Sale', false)
            ->assertSee('Spesifikasi', false);
    }

    public function test_product_media_data_and_zip_download()
    {
        $service = app(MarketingKitService::class);
        $data = $service->getMarketingKitData($this->product);

        $this->assertArrayHasKey('media', $data);
        $this->assertArrayHasKey('download_media_url', $data);
        $this->assertNotEmpty($data['media']);

        $superAdmin = User::factory()->create();
        if (method_exists($superAdmin, 'assignRole')) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super_admin']);
            $superAdmin->assignRole('super_admin');
        }

        $response = $this->actingAs($superAdmin)->get(route('admin.products.download-media', $this->product->id));
        $response->assertStatus(200);
        $this->assertStringContainsString('application/zip', $response->headers->get('content-type'));
    }
}
