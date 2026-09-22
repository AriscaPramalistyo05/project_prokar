<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class ProductMediaController extends Controller
{
    /**
     * Download all images and videos for a product as a ZIP archive.
     */
    public function downloadMedia(Product $product): BinaryFileResponse|RedirectResponse
    {
        $images = $product->productImages()->orderBy('order')->get();
        $zipFileName = 'media-' . ($product->slug ?: 'produk-' . $product->id) . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $idx = 1;
            foreach ($images as $img) {
                $cleanPath = ltrim($img->path, '/');
                if (str_starts_with($cleanPath, 'storage/')) {
                    $cleanPath = substr($cleanPath, 8);
                }
                $fullPath = storage_path('app/public/' . $cleanPath);
                if (file_exists($fullPath)) {
                    $ext = pathinfo($fullPath, PATHINFO_EXTENSION) ?: ($img->type === 'video' ? 'mp4' : 'jpg');
                    $prefix = $img->type === 'video' ? 'video' : 'foto';
                    $zip->addFile($fullPath, "{$prefix}-{$idx}-{$product->slug}.{$ext}");
                    $idx++;
                }
            }

            if ($zip->numFiles === 0 && file_exists(public_path('images/logo prokar.png'))) {
                $zip->addFile(public_path('images/logo prokar.png'), "foto-1-{$product->slug}.png");
            }

            $zip->close();
        }

        if (file_exists($zipPath) && filesize($zipPath) > 0) {
            return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Tidak ada media yang dapat diunduh untuk produk ini.');
    }
}
