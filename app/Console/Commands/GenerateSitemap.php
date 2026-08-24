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

    public function handle(): int
    {
        $this->info('Generating sitemap...');

        $sitemap = Sitemap::create();

        // 1. Static Public Pages
        $sitemap->add(Url::create(route('home'))->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemap->add(Url::create(route('produk.index'))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        
        if (\Illuminate\Support\Facades\Route::has('servis')) {
            $sitemap->add(Url::create(route('servis'))->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        }
        if (\Illuminate\Support\Facades\Route::has('jual')) {
            $sitemap->add(Url::create(route('jual'))->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        }
        if (\Illuminate\Support\Facades\Route::has('tentang.kami')) {
            $sitemap->add(Url::create(route('tentang.kami'))->setPriority(0.6)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
        }
        if (\Illuminate\Support\Facades\Route::has('kontak')) {
            $sitemap->add(Url::create(route('kontak'))->setPriority(0.6)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
        }
        if (\Illuminate\Support\Facades\Route::has('syarat.ketentuan')) {
            $sitemap->add(Url::create(route('syarat.ketentuan'))->setPriority(0.4)->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY));
        }
        if (\Illuminate\Support\Facades\Route::has('kebijakan.privasi')) {
            $sitemap->add(Url::create(route('kebijakan.privasi'))->setPriority(0.4)->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY));
        }

        // 2. Categories
        Category::all()->each(function (Category $category) use ($sitemap) {
            $sitemap->add(
                Url::create(route('produk.index', ['kategori' => $category->slug]))
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });

        // 3. Products (Available & Sold for canonical SEO value)
        Product::all()->each(function (Product $product) use ($sitemap) {
            $priority = $product->status === 'available' ? 0.8 : 0.5;
            $sitemap->add(
                Url::create(route('produk.show', $product->slug))
                    ->setLastModificationDate($product->updated_at)
                    ->setPriority($priority)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap successfully generated at ' . public_path('sitemap.xml'));
        return Command::SUCCESS;
    }
}
