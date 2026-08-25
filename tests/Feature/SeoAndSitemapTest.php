<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SeoAndSitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_artisan_sitemap_generate_command_creates_valid_sitemap_xml(): void
    {
        $exitCode = Artisan::call('sitemap:generate');
        $this->assertEquals(0, $exitCode);

        $sitemapPath = public_path('sitemap.xml');
        $this->assertFileExists($sitemapPath);
        $this->assertNotEmpty(File::get($sitemapPath));
    }

    public function test_sitemap_includes_public_pages_categories_and_all_products(): void
    {
        $category = Category::factory()->create([
            'name' => 'Kulkas Showcase',
            'slug' => 'kulkas-showcase',
        ]);

        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Kulkas Polytron Belleza Varian Khusus',
            'slug' => 'kulkas-polytron-belleza-khusus',
        ]);

        Artisan::call('sitemap:generate');

        $sitemapContent = File::get(public_path('sitemap.xml'));

        $this->assertStringContainsString('kulkas-polytron-belleza-khusus', $sitemapContent);
        $this->assertStringContainsString('kulkas-showcase', $sitemapContent);
        $this->assertStringContainsString('/produk', $sitemapContent);
    }

    public function test_robots_txt_allows_public_and_blocks_admin_checkout_profile(): void
    {
        $robotsPath = public_path('robots.txt');
        $this->assertFileExists($robotsPath);

        $robotsContent = File::get($robotsPath);

        $this->assertStringContainsString('Allow: /produk', $robotsContent);
        $this->assertStringContainsString('Disallow: /admin', $robotsContent);
        $this->assertStringContainsString('Disallow: /checkout', $robotsContent);
        $this->assertStringContainsString('Disallow: /profil', $robotsContent);
        $this->assertStringContainsString('Sitemap:', $robotsContent);
    }

    public function test_product_detail_contains_opengraph_and_json_ld_schema(): void
    {
        $category = Category::factory()->create(['name' => 'Televisi']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Smart TV Xiaomi 43 Inch 4K',
            'slug' => 'smart-tv-xiaomi-43-inch-4k',
            'price' => 2500000,
        ]);

        $response = $this->get(route('produk.show', $product->slug));

        $response->assertOk();
        $response->assertSee('Smart TV Xiaomi 43 Inch 4K');
        $response->assertSee('application/ld+json', false);
        $response->assertSee('"@type":"Product"', false);
        $response->assertSee('property="og:type"', false);
        $response->assertSee('content="product"', false);
    }
}
