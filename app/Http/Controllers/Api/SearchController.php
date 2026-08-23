<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    /**
     * Top trending searches (24h cache).
     */
    protected const TOP_SEARCHES = [
        ['rank' => 1, 'query' => 'Kulkas 2 Pintu', 'trend' => 'neutral'],
        ['rank' => 2, 'query' => 'Smart TV 4K', 'trend' => 'neutral'],
        ['rank' => 3, 'query' => 'Mesin Cuci 1 Tabung', 'trend' => 'neutral'],
        ['rank' => 4, 'query' => 'AC Split Low Watt', 'trend' => 'neutral'],
        ['rank' => 5, 'query' => 'Microwave Digital', 'trend' => 'neutral'],
        ['rank' => 6, 'query' => 'Dispenser Galon Bawah', 'trend' => 'neutral'],
        ['rank' => 7, 'query' => 'Kipas Angin Berdiri', 'trend' => 'up'],
        ['rank' => 8, 'query' => 'Vacuum Cleaner', 'trend' => 'down'],
        ['rank' => 9, 'query' => 'Service TV', 'trend' => 'neutral'],
        ['rank' => 10, 'query' => 'Sharp Polytron LG', 'trend' => 'neutral'],
    ];

    /**
     * Fast search endpoint with query caching and lightweight payload.
     */
    public function search(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([
                'success' => true,
                'query' => $q,
                'top_searches' => self::TOP_SEARCHES,
                'results' => [],
            ])->header('Cache-Control', 'public, max-age=300');
        }

        $cacheKey = 'search_instant_' . md5(mb_strtolower($q));

        $results = Cache::remember($cacheKey, 180, function () use ($q) {
            return Product::with(['category:id,name,slug', 'primaryImage'])
                ->where('status', 'available')
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                        ->orWhere('brand', 'like', "%{$q}%")
                        ->orWhere('model', 'like', "%{$q}%")
                        ->orWhereHas('category', function ($catQuery) use ($q) {
                            $catQuery->where('name', 'like', "%{$q}%");
                        });
                })
                ->select(['id', 'category_id', 'name', 'slug', 'price', 'promo_price', 'is_promo'])
                ->take(6)
                ->get()
                ->map(function ($product) {
                    $activePrice = $product->promo_price && $product->is_promo
                        ? (float) $product->promo_price
                        : (float) $product->price;

                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'category' => $product->category?->name ?? 'Elektronik',
                        'price' => $activePrice,
                        'formatted_price' => 'Rp ' . number_format($activePrice, 0, ',', '.'),
                        'image' => $product->image_url,
                        'url' => route('produk.show', $product->slug),
                    ];
                })
                ->toArray();
        });

        return response()->json([
            'success' => true,
            'query' => $q,
            'top_searches' => self::TOP_SEARCHES,
            'results' => $results,
        ])->header('Cache-Control', 'public, max-age=180');
    }
}
