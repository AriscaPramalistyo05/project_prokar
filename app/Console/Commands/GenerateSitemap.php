<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.xml file for Google indexing';

    /**
     * Get canonical URL avoiding private LAN IP leaks in sitemap
     */
    private function canonicalUrl(string $path = '/'): string
    {
        $base = config('app.url', 'https://prokarelektronik.com');
        
        // If base is a LAN IP (e.g. 192.168.x.x or 10.x.x.x or 172.16.x.x or localhost), fallback to official domain
        if (preg_match('/^(https?:\/\/)?(192\.168\.|10\.|172\.(1[6-9]|2[0-9]|3[0-1])\.|127\.0\.0\.1|localhost)/i', $base)) {
            $base = 'https://prokarelektronik.com';
        }

        return rtrim($base, '/') . '/' . ltrim($path, '/');
    }

    public function handle(): int
    {
        $this->info('Generating sitemap with canonical domain...');

        $sitemap = Sitemap::create();

        // 1. Static Public Pages
        $sitemap->add(Url::create($this->canonicalUrl('/'))->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemap->add(Url::create($this->canonicalUrl('/produk'))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemap->add(Url::create($this->canonicalUrl('/servis'))->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        $sitemap->add(Url::create($this->canonicalUrl('/jual'))->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        $sitemap->add(Url::create($this->canonicalUrl('/syarat-ketentuan'))->setPriority(0.4)->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY));
        $sitemap->add(Url::create($this->canonicalUrl('/kebijakan-privasi'))->setPriority(0.4)->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY));

        // 2. Categories
        Category::all()->each(function (Category $category) use ($sitemap) {
            $sitemap->add(
                Url::create($this->canonicalUrl('/produk?kategori=' . urlencode($category->slug)))
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });

        // 3. Products (Available & Sold for canonical SEO value)
        Product::all()->each(function (Product $product) use ($sitemap) {
            $priority = $product->status === 'available' ? 0.8 : 0.5;
            $sitemap->add(
                Url::create($this->canonicalUrl('/produk/' . urlencode($product->slug)))
                    ->setLastModificationDate($product->updated_at ?? now())
                    ->setPriority($priority)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap successfully generated at ' . public_path('sitemap.xml'));
        return Command::SUCCESS;
    }
}
