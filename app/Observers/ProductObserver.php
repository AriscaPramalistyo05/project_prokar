<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "saving" event.
     */
    public function saving(Product $product): void
    {
        // If stock is 0, status is automatically 'sold'
        if ($product->stock === 0) {
            $product->status = 'sold';
        } elseif ($product->stock > 0 && $product->status === 'sold') {
            // Restore status to available if stock becomes > 0 and status was 'sold'
            $product->status = 'available';
        }
    }
}
