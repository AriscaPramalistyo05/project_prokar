<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class PwaService
{
    /**
     * Standard icon sizes required for PWA and various devices.
     */
    public const ICON_SIZES = [
        'icon-192x192.png' => ['width' => 192, 'height' => 192, 'purpose' => 'any maskable'],
        'icon-512x512.png' => ['width' => 512, 'height' => 512, 'purpose' => 'any maskable'],
        'apple-touch-icon.png' => ['width' => 180, 'height' => 180, 'purpose' => 'any'],
        'favicon-32x32.png' => ['width' => 32, 'height' => 32, 'purpose' => 'any'],
    ];

    /**
     * Generate multi-resolution icons from an uploaded file or existing image path.
     *
     * @param UploadedFile|string $source
     * @return array Array of generated icon paths
     */
    public static function generateIcons(UploadedFile|string $source): array
    {
        $iconsDir = public_path('icons');
        if (!File::isDirectory($iconsDir)) {
            File::makeDirectory($iconsDir, 0755, true);
        }

        $generated = [];

        try {
            $manager = new ImageManager(new Driver());
            $sourcePath = $source instanceof UploadedFile ? $source->getRealPath() : (is_file($source) ? $source : public_path($source));

            if (!file_exists($sourcePath)) {
                // If stored in storage/app/public
                $storagePath = storage_path('app/public/' . ltrim($source, '/'));
                if (file_exists($storagePath)) {
                    $sourcePath = $storagePath;
                }
            }

            if (!file_exists($sourcePath)) {
                Log::warning("PwaService: Source image not found: {$sourcePath}");
                return [];
            }

            foreach (self::ICON_SIZES as $filename => $spec) {
                $targetPath = $iconsDir . '/' . $filename;
                
                try {
                    $image = $manager->decodePath($sourcePath);
                    $image->cover($spec['width'], $spec['height']);
                    $encoded = $image->encodeUsingFileExtension('png');
                    File::put($targetPath, (string) $encoded);
                    $generated[$filename] = '/icons/' . $filename;
                } catch (\Throwable $e) {
                    Log::error("PwaService: Failed to generate {$filename}: " . $e->getMessage());
                }
            }

            // Also copy apple-touch-icon.png directly to root public if generated
            if (file_exists($iconsDir . '/apple-touch-icon.png')) {
                File::copy($iconsDir . '/apple-touch-icon.png', public_path('apple-touch-icon.png'));
            }

            // Re-generate manifest.json with updated icon references
            self::generateManifest();

        } catch (\Throwable $e) {
            Log::error("PwaService: Error during icon generation: " . $e->getMessage());
        }

        return $generated;
    }

    /**
     * Generates or updates public/manifest.json based on store settings.
     */
    public static function generateManifest(): void
    {
        $shopName = setting('shop_name', 'Prokar Elektronik');
        $shopTagline = setting('shop_tagline', 'Jual, Beli & Servis Elektronik Bekas Terpercaya');
        $themeColor = '#0A0A0A';
        $bgColor = '#FFFFFF';

        $icons = [
            [
                'src' => '/icons/icon-192x192.png',
                'sizes' => '192x192',
                'type' => 'image/png',
                'purpose' => 'any maskable'
            ],
            [
                'src' => '/icons/icon-512x512.png',
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'any maskable'
            ],
        ];

        // Fallback to default logo if icons/icon-192x192.png doesn't exist
        if (!file_exists(public_path('icons/icon-192x192.png'))) {
            $icons = [
                [
                    'src' => '/images/logo prokar.png',
                    'sizes' => '192x192 512x512',
                    'type' => 'image/png',
                    'purpose' => 'any'
                ]
            ];
        }

        $manifestData = [
            'name' => $shopName . ' – ' . $shopTagline,
            'short_name' => 'Prokar Elektronik',
            'description' => 'Platform jual, beli, dan servis elektronik bekas terpercaya di Jepara dengan garansi toko resmi.',
            'start_url' => '/',
            'scope' => '/',
            'display' => 'standalone',
            'orientation' => 'portrait',
            'background_color' => $bgColor,
            'theme_color' => $themeColor,
            'lang' => 'id',
            'categories' => ['shopping', 'business', 'utilities'],
            'icons' => $icons,
            'shortcuts' => [
                [
                    'name' => 'Katalog Produk',
                    'url' => '/produk',
                    'description' => 'Lihat produk elektronik bekas bergaransi'
                ],
                [
                    'name' => 'Layanan Servis',
                    'url' => '/servis',
                    'description' => 'Ajukan servis perbaikan elektronik'
                ],
                [
                    'name' => 'Lacak Servis',
                    'url' => '/servis/lacak',
                    'description' => 'Lacak progres pengerjaan servis'
                ],
                [
                    'name' => 'Jual Elektronik',
                    'url' => '/jual',
                    'description' => 'Ajukan penjualan barang elektronik bekas'
                ]
            ]
        ];

        File::put(public_path('manifest.json'), json_encode($manifestData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
