<?php

namespace App\Services;

use App\Models\Product;

class MarketingKitService
{
    /**
     * Format nomor WhatsApp untuk URL internasional (62xxxx)
     */
    public function formatWhatsAppNumber(?string $phone): string
    {
        if (empty($phone)) {
            $phone = (string) setting('shop_whatsapp', '081234567890');
        }

        // Hapus karakter non-digit
        $clean = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        } elseif (str_starts_with($clean, '+62')) {
            $clean = substr($clean, 1);
        }

        return $clean ?: '6281234567890';
    }

    /**
     * Dapatkan URL publik produk
     */
    public function getProductUrl(Product $product): string
    {
        return url('/produk/' . ($product->slug ?: $product->id));
    }

    /**
     * Generate Copywriting promosi berdasarkan template yang dipilih
     * 
     * @param Product $product
     * @param string $template 'standard' | 'promo' | 'technical'
     * @return string
     */
    public function generateCopywriting(Product $product, string $template = 'standard'): string
    {
        $shopName = setting('shop_name', 'Prokar Elektronik');
        $shopAddress = setting('shop_address', 'Jepara, Jawa Tengah');
        $waNumber = $this->formatWhatsAppNumber(setting('shop_whatsapp'));
        $productUrl = $this->getProductUrl($product);
        
        $priceFormatted = format_rupiah($product->price);
        $promoFormatted = $product->promo_price ? format_rupiah($product->promo_price) : null;
        $condition = ucwords(str_replace('_', ' ', $product->condition ?? 'Baik'));
        $conditionNotes = $product->condition_notes ?: 'Unit normal siap pakai, fisik mulus, dan sudah melalui quality control teknisi.';
        $brandModel = trim(($product->brand ?? '') . ' ' . ($product->model ?? ''));

        switch ($template) {
            case 'promo':
            case 'flash_sale':
                $priceText = $promoFormatted 
                    ? "💥 *Harga Promo: {$promoFormatted}*\n❌ Harga Normal: ~{$priceFormatted}~"
                    : "💥 *Harga Spesial: {$priceFormatted}*";

                $lines = [];
                $lines[] = "⚡ *PROMO FLASH SALE - {$shopName}* ⚡";
                $lines[] = "";
                $lines[] = "Yuk amankan segera, unit pilihan terbatas!";
                $lines[] = "📦 *{$product->name}*";
                if (!empty($brandModel)) {
                    $lines[] = "🏷️ Tipe: {$brandModel}";
                }
                $lines[] = "";
                $lines[] = $priceText;
                $lines[] = "";
                if (!empty($condition)) {
                    $lines[] = "✅ Kondisi: {$condition}";
                }
                if (!empty(trim($product->condition_notes ?? ''))) {
                    $lines[] = "✅ Catatan: " . trim($product->condition_notes);
                }
                $lines[] = "📍 Lokasi: {$shopAddress}";
                $lines[] = "🚚 Siap kirim atau cek langsung ke toko.";
                $lines[] = "";
                $lines[] = "👉 Cek foto & detail lengkap:\n{$productUrl}";
                $lines[] = "";
                $lines[] = "📲 Pesan Cepat via WA:\nhttps://wa.me/{$waNumber}?text=" . urlencode("Halo {$shopName}, saya mau pesan produk promo {$product->name} ({$productUrl})");

                return implode("\n", $lines);

            case 'technical':
            case 'spec':
            case 'spesifikasi':
                $priceDisplay = ($product->is_promo && $promoFormatted) 
                    ? "{$promoFormatted} (Promo) | Normal: ~{$priceFormatted}~" 
                    : $priceFormatted;

                $categoryName = null;
                try {
                    $categoryName = $product->category?->name;
                } catch (\Throwable $e) {
                    $categoryName = null;
                }

                $lines = [];
                $lines[] = "📋 *SPESIFIKASI & DETAIL PRODUK - {$shopName}*";
                $lines[] = "";
                $lines[] = "📦 *{$product->name}*";
                $lines[] = "💰 *Harga:* {$priceDisplay}";
                $lines[] = "";
                $lines[] = "🔍 *Detail Spesifikasi Unit:*";

                if (!empty($categoryName)) {
                    $lines[] = "• Kategori: {$categoryName}";
                }
                if (!empty(trim($product->brand ?? ''))) {
                    $lines[] = "• Merk / Brand: " . trim($product->brand);
                }
                if (!empty(trim($product->model ?? ''))) {
                    $lines[] = "• Model / Tipe: " . trim($product->model);
                }
                if (!empty(trim($product->condition ?? ''))) {
                    $lines[] = "• Kondisi: " . ucwords(str_replace('_', ' ', trim($product->condition)));
                }
                if (!empty(trim($product->condition_notes ?? ''))) {
                    $lines[] = "• Catatan Kondisi: " . trim($product->condition_notes);
                }
                if (!empty($product->weight) && $product->weight > 0) {
                    $formattedWeight = ($product->weight >= 1000)
                        ? rtrim(rtrim(number_format($product->weight / 1000, 2, ',', '.'), '0'), ',') . ' kg'
                        : $product->weight . ' gram';
                    $lines[] = "• Berat: {$formattedWeight}";
                }
                if (!empty($product->length) && !empty($product->width) && !empty($product->height)) {
                    $lines[] = "• Dimensi (P×L×T): {$product->length} × {$product->width} × {$product->height} cm";
                } elseif (!empty($product->length) || !empty($product->width) || !empty($product->height)) {
                    $dimParts = [];
                    if (!empty($product->length)) $dimParts[] = "P: {$product->length}cm";
                    if (!empty($product->width)) $dimParts[] = "L: {$product->width}cm";
                    if (!empty($product->height)) $dimParts[] = "T: {$product->height}cm";
                    $lines[] = "• Dimensi: " . implode(' × ', $dimParts);
                }
                if ($product->stock !== null) {
                    $statusLabel = match($product->status) {
                        'available' => 'Tersedia',
                        'sold' => 'Sudah Terjual',
                        'reserved' => 'Dipesan',
                        'unavailable' => 'Tidak Tersedia',
                        default => ucwords($product->status ?? 'Tersedia')
                    };
                    $lines[] = "• Status: {$statusLabel}" . ($product->stock > 0 ? " ({$product->stock} unit)" : "");
                }

                if (!empty(trim($product->description ?? ''))) {
                    $cleanDesc = trim(strip_tags($product->description));
                    $lines[] = "";
                    $lines[] = "📝 *Deskripsi & Spesifikasi Lengkap:*";
                    $lines[] = $cleanDesc;
                }

                $lines[] = "";
                $lines[] = "📍 Lokasi: {$shopAddress}";
                $lines[] = "🔗 Cek foto & detail lengkap:\n{$productUrl}";
                $lines[] = "";
                $lines[] = "📲 Hubungi Admin Toko:\nhttps://wa.me/{$waNumber}?text=" . urlencode("Halo {$shopName}, saya mau tanya detail spesifikasi {$product->name} ({$productUrl})");

                return implode("\n", $lines);

            case 'standard':
            default:
                $priceDisplay = ($product->is_promo && $promoFormatted) ? "{$promoFormatted} (Harga Normal: ~{$priceFormatted}~)" : $priceFormatted;

                $lines = [];
                $lines[] = "🔥 *{$product->name}*";
                $lines[] = "";
                $lines[] = "Ready di *{$shopName}*:";
                $lines[] = "🏷️ Harga: {$priceDisplay}";
                if (!empty($condition)) {
                    $lines[] = "✨ Kondisi: {$condition}";
                }
                if (!empty(trim($product->condition_notes ?? ''))) {
                    $lines[] = "📝 Catatan: " . trim($product->condition_notes);
                }
                $lines[] = "";
                $lines[] = "📍 Toko: {$shopAddress}";
                $lines[] = "🛒 Cek spesifikasi lengkap & checkout aman:\n{$productUrl}";
                $lines[] = "";
                $lines[] = "📲 Chat WhatsApp:\nhttps://wa.me/{$waNumber}?text=" . urlencode("Halo {$shopName}, apakah produk {$product->name} masih ready? ({$productUrl})");

                return implode("\n", $lines);
        }
    }

    /**
     * Dapatkan URL Share WhatsApp (General Share / Broadcast ke Grup)
     */
    public function getWhatsAppShareUrl(Product $product, string $template = 'standard'): string
    {
        $text = $this->generateCopywriting($product, $template);
        return 'https://api.whatsapp.com/send?text=' . rawurlencode($text);
    }

    /**
     * Dapatkan URL WhatsApp untuk Direct Order ke Admin Toko
     */
    public function getWhatsAppDirectOrderUrl(Product $product): string
    {
        $waNumber = $this->formatWhatsAppNumber(setting('shop_whatsapp'));
        $shopName = setting('shop_name', 'Prokar Elektronik');
        $productUrl = $this->getProductUrl($product);
        $msg = "Halo {$shopName}, saya tertarik dan ingin pesan produk:\n*{$product->name}*\nLink: {$productUrl}";
        return "https://wa.me/{$waNumber}?text=" . rawurlencode($msg);
    }

    /**
     * Dapatkan URL Share Facebook
     */
    public function getFacebookShareUrl(Product $product): string
    {
        $productUrl = $this->getProductUrl($product);
        return 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($productUrl);
    }

    /**
     * Dapatkan URL Share Telegram
     */
    public function getTelegramShareUrl(Product $product, string $template = 'standard'): string
    {
        $productUrl = $this->getProductUrl($product);
        $text = "🔥 *" . $product->name . "*\nHarga: " . ($product->promo_price ? format_rupiah($product->promo_price) : format_rupiah($product->price));
        return 'https://t.me/share/url?url=' . urlencode($productUrl) . '&text=' . rawurlencode($text);
    }

    /**
     * Dapatkan semua data marketing kit lengkap dalam bentuk array untuk frontend / API / Livewire
     */
    public function getMarketingKitData(Product $product): array
    {
        $imageUrl = asset('images/logo prokar.png');
        try {
            $imageUrl = $product->image_url;
        } catch (\Throwable $e) {
            $imageUrl = asset('images/logo prokar.png');
        }

        $mediaList = [];
        try {
            $images = $product->productImages()->orderBy('order')->get();
            foreach ($images as $img) {
                $mediaList[] = [
                    'id' => $img->id,
                    'url' => $img->url,
                    'type' => $img->type ?: 'image',
                    'is_primary' => (bool) $img->is_primary,
                ];
            }
        } catch (\Throwable $e) {
            $mediaList = [];
        }

        if (empty($mediaList)) {
            $mediaList[] = [
                'id' => null,
                'url' => $imageUrl,
                'type' => 'image',
                'is_primary' => true,
            ];
        }

        $imageCount = count(array_filter($mediaList, fn($m) => ($m['type'] ?? 'image') !== 'video'));
        $videoCount = count(array_filter($mediaList, fn($m) => ($m['type'] ?? '') === 'video'));
        $hasPromo = (bool) ($product->is_promo && $product->promo_price && (float) $product->promo_price > 0);

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'url' => $this->getProductUrl($product),
            'brand' => $product->brand,
            'model' => $product->model,
            'price' => (float) $product->price,
            'price_formatted' => format_rupiah($product->price),
            'promo_price' => $product->promo_price ? (float) $product->promo_price : null,
            'promo_price_formatted' => $hasPromo ? format_rupiah($product->promo_price) : null,
            'is_promo' => (bool) $product->is_promo,
            'has_promo' => $hasPromo,
            'condition' => ucwords(str_replace('_', ' ', $product->condition ?? 'Baik')),
            'condition_notes' => $product->condition_notes,
            'image_url' => $imageUrl,
            'media' => $mediaList,
            'image_count' => $imageCount,
            'video_count' => $videoCount,
            'media_count' => count($mediaList),
            'download_media_url' => route('admin.products.download-media', ['product' => $product->id]),
            'shop_name' => setting('shop_name', 'Prokar Elektronik'),
            'shop_whatsapp' => setting('shop_whatsapp', '081234567890'),
            'shop_address' => setting('shop_address', 'Jepara, Jawa Tengah'),
            'copywriting' => [
                'standard' => $this->generateCopywriting($product, 'standard'),
                'promo' => $hasPromo ? $this->generateCopywriting($product, 'promo') : '',
                'technical' => $this->generateCopywriting($product, 'technical'),
            ],
            'share_urls' => [
                'whatsapp' => $this->getWhatsAppShareUrl($product, 'standard'),
                'whatsapp_direct' => $this->getWhatsAppDirectOrderUrl($product),
                'facebook' => $this->getFacebookShareUrl($product),
                'telegram' => $this->getTelegramShareUrl($product),
            ],
        ];
    }
}
