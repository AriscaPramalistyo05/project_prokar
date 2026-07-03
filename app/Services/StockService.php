<?php

namespace App\Services;

use App\Exceptions\ProductUnavailableException;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Reserve stock for a product using pessimistic locking.
     *
     * Prevents race conditions when multiple users try to buy the same product
     * simultaneously. Uses DB::transaction + lockForUpdate (SELECT ... FOR UPDATE)
     * so concurrent requests will wait in queue instead of reading stale data.
     *
     * @param  int  $productId  The product ID to reserve stock for.
     * @param  int  $qty        Quantity to reserve (default 1, karena barang bekas biasanya unik).
     * @return Product           The updated product instance.
     *
     * @throws ProductUnavailableException  When product is not available or stock is insufficient.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException  When product ID doesn't exist.
     */
    public function reserveStock(int $productId, int $qty = 1): Product
    {
        return DB::transaction(function () use ($productId, $qty) {
            // lockForUpdate() → SELECT ... FOR UPDATE (row-level lock)
            // Concurrent requests will block here until this transaction commits.
            $product = Product::lockForUpdate()->findOrFail($productId);

            if ($product->status !== 'available') {
                throw new ProductUnavailableException(
                    "Produk \"{$product->name}\" tidak tersedia (status: {$product->status})."
                );
            }

            if ($product->stock < $qty) {
                throw new ProductUnavailableException(
                    "Stok produk \"{$product->name}\" tidak mencukupi (sisa: {$product->stock}, diminta: {$qty})."
                );
            }

            $product->decrement('stock', $qty);

            // Refresh model to get updated stock value after decrement
            $product->refresh();

            // ProductObserver will handle status change to 'sold' if stock === 0
            // but decrement() bypasses Eloquent events, so we handle it explicitly.
            if ($product->stock === 0) {
                $product->update(['status' => 'sold']);
            }

            return $product;
        });
    }

    /**
     * Release previously reserved stock (e.g. order cancelled, payment expired).
     *
     * @param  int  $productId  The product ID to release stock for.
     * @param  int  $qty        Quantity to release back.
     * @return Product           The updated product instance.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException  When product ID doesn't exist.
     */
    public function releaseStock(int $productId, int $qty = 1): Product
    {
        return DB::transaction(function () use ($productId, $qty) {
            $product = Product::lockForUpdate()->findOrFail($productId);

            $product->increment('stock', $qty);

            // Refresh model to get updated stock value after increment
            $product->refresh();

            // If product was 'sold' and now has stock, restore to 'available'
            if ($product->stock > 0 && $product->status === 'sold') {
                $product->update(['status' => 'available']);
            }

            return $product;
        });
    }
}
