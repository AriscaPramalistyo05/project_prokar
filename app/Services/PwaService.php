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
        'icon-192x192.png' => ['width' => 192, 'height' => 192, 'purpose' => 'any'],
        'icon-512x512.png' => ['width' => 512, 'height' => 512, 'purpose' => 'any'],
        'icon-maskable-192x192.png' => ['width' => 192, 'height' => 192, 'purpose' => 'maskable'],
        'icon-maskable-512x512.png' => ['width' => 512, 'height' => 512, 'purpose' => 'maskable'],
        'apple-touch-icon.png' => ['width' => 180, 'height' => 180, 'purpose' => 'any'],
        'favicon-32x32.png' => ['width' => 32, 'height' => 32, 'purpose' => 'any'],
    ];

    /**
     * Generate multi-resolution icons with Prokar Brand Yellow background (#FFCC00).
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

            // Create yellow master composite
            $src = @imagecreatefrompng($sourcePath);
            if (!$src) {
                $src = @imagecreatefromstring(file_get_contents($sourcePath));
            }

            if ($src) {
                $width = imagesx($src);
                $height = imagesy($src);

                $yellow = imagecreatetruecolor($width, $height);
                $yellowColor = imagecolorallocate($yellow, 255, 204, 0); // #FFCC00
                $blackColor = imagecolorallocate($yellow, 15, 23, 42); // Sleek dark #0F172A

                imagefill($yellow, 0, 0, $yellowColor);

                for ($x = 0; $x < $width; $x++) {
                    for ($y = 0; $y < $height; $y++) {
                        $rgb = imagecolorat($src, $x, $y);
                        $alpha = ($rgb >> 24) & 0x7F;
                        $r = ($rgb >> 16) & 0xFF;
                        $g = ($rgb >> 8) & 0xFF;
                        $b = $rgb & 0xFF;

                        if ($alpha < 64) {
                            $luminance = ($r * 0.299 + $g * 0.587 + $b * 0.114);
                            if ($luminance < 180) {
                                imagesetpixel($yellow, $x, $y, $blackColor);
                            }
                        }
                    }
                }

                $tempYellow = storage_path('app/temp_pwa_yellow.png');
                imagepng($yellow, $tempYellow);
                imagedestroy($src);
                imagedestroy($yellow);

                $manager = new ImageManager(new Driver());

                foreach (self::ICON_SIZES as $filename => $spec) {
                    $targetPath = $iconsDir . '/' . $filename;
                    try {
                        $img = $manager->decodePath($tempYellow);
                        $img->contain($spec['width'], $spec['height'], '#FFCC00');
                        $encoded = $img->encodeUsingFileExtension('png');
                        File::put($targetPath, (string) $encoded);
                        $generated[$filename] = '/icons/' . $filename;
                    } catch (\Throwable $e) {
                        Log::error("PwaService: Failed to generate {$filename}: " . $e->getMessage());
                    }
                }

                @unlink($tempYellow);
            }

            // Copy main icons to root public directory for maximum Android, Chrome & iOS compatibility
            if (file_exists($iconsDir . '/apple-touch-icon.png')) {
                File::copy($iconsDir . '/apple-touch-icon.png', public_path('apple-touch-icon.png'));
            }
            if (file_exists($iconsDir . '/icon-192x192.png')) {
                File::copy($iconsDir . '/icon-192x192.png', public_path('icon-192x192.png'));
            }
            if (file_exists($iconsDir . '/icon-512x512.png')) {
                File::copy($iconsDir . '/icon-512x512.png', public_path('icon-512x512.png'));
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
        $themeColor = '#FFCC00';
        $bgColor = '#FFCC00';

        $icons = [
            [
                'src' => '/icons/icon-192x192.png',
                'sizes' => '192x192',
                'type' => 'image/png',
                'purpose' => 'any'
            ],
            [
                'src' => '/icons/icon-512x512.png',
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'any'
            ],
            [
                'src' => '/icons/icon-maskable-192x192.png',
                'sizes' => '192x192',
                'type' => 'image/png',
                'purpose' => 'maskable'
            ],
            [
                'src' => '/icons/icon-maskable-512x512.png',
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'maskable'
            ]
        ];

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
