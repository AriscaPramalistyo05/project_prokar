<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartService
{
    protected const SESSION_KEY = 'cart';

    /**
     * Get all cart items enriched with product data from DB.
     * Merges session cart to database if user is authenticated.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getItems(): array
    {
        if (Auth::check()) {
            $this->syncSessionToDatabase();
            return $this->getItemsFromDatabase();
        }

        return $this->getItemsFromSession();
    }

    /**
     * Add a product to the cart.
     */
    public function addItem(int $productId, int $qty = 1): bool
    {
        $qty = max(1, $qty);
        $product = Product::find($productId);
        if (!$product || $product->status !== 'available' || $product->stock < 1) {
            return false;
        }

        if (Auth::check()) {
            $this->syncSessionToDatabase();
            $userId = Auth::id();

            $existing = CartItem::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            $newQty = $existing ? min($product->stock, $existing->quantity + $qty) : min($product->stock, $qty);

            CartItem::updateOrCreate(
                ['user_id' => $userId, 'product_id' => $productId],
                ['quantity' => $newQty]
            );

            $this->syncDatabaseToSession();
            return true;
        }

        $cart = session(self::SESSION_KEY, []);

        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] = min(
                $product->stock,
                ($cart[$productId]['qty'] ?? 1) + $qty
            );
        } else {
            $cart[$productId] = ['qty' => min($product->stock, $qty)];
        }

        session([self::SESSION_KEY => $cart]);
        return true;
    }

    /**
     * Update quantity for a specific product.
     */
    public function updateQty(int $productId, int $qty): void
    {
        $product = Product::find($productId);
        $maxQty = $product ? (int) $product->stock : 1;
        $targetQty = max(1, min($maxQty, $qty));

        if (Auth::check()) {
            $userId = Auth::id();
            CartItem::where('user_id', $userId)
                ->where('product_id', $productId)
                ->update(['quantity' => $targetQty]);

            $this->syncDatabaseToSession();
            return;
        }

        $cart = session(self::SESSION_KEY, []);
        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] = $targetQty;
            session([self::SESSION_KEY => $cart]);
        }
    }

    /**
     * Remove a product from the cart.
     */
    public function removeItem(int $productId): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->delete();

            $this->syncDatabaseToSession();
            return;
        }

        $cart = session(self::SESSION_KEY, []);
        unset($cart[$productId]);
        session([self::SESSION_KEY => $cart]);
    }

    /**
     * Calculate subtotal of all items.
     */
    public function subtotal(): int
    {
        return array_sum(array_map(
            fn ($item) => $item['unit_price'] * $item['quantity'],
            $this->getItems()
        ));
    }

    /**
     * Count unique products in the cart.
     */
    public function count(): int
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id())->count();
        }

        return count(session(self::SESSION_KEY, []));
    }

    /**
     * Total quantity of all items.
     */
    public function totalQty(): int
    {
        if (Auth::check()) {
            return (int) CartItem::where('user_id', Auth::id())->sum('quantity');
        }

        $cart = session(self::SESSION_KEY, []);
        return array_sum(array_column($cart, 'qty'));
    }

    /**
     * Clear the entire cart from DB & session (after successful order/checkout).
     */
    public function clear(): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        }

        session()->forget(self::SESSION_KEY);
    }

    /**
     * Move temporary session cart items to database upon user login/auth.
     */
    public function syncSessionToDatabase(): void
    {
        if (!Auth::check()) {
            return;
        }

        $sessionCart = session(self::SESSION_KEY, []);
        $userId = Auth::id();

        if (!empty($sessionCart)) {
            foreach ($sessionCart as $productId => $data) {
                $product = Product::find($productId);
                if (!$product) {
                    continue;
                }

                $qty = (int) ($data['qty'] ?? 1);
                $existing = CartItem::where('user_id', $userId)
                    ->where('product_id', $productId)
                    ->first();

                $finalQty = $existing
                    ? min($product->stock, $existing->quantity + $qty)
                    : min($product->stock, $qty);

                CartItem::updateOrCreate(
                    ['user_id' => $userId, 'product_id' => $productId],
                    ['quantity' => $finalQty]
                );
            }

            session()->forget(self::SESSION_KEY);
        }

        $this->syncDatabaseToSession();
    }

    /**
     * Mirror current database cart items into session array for fast access.
     */
    protected function syncDatabaseToSession(): void
    {
        if (!Auth::check()) {
            return;
        }

        $dbItems = CartItem::where('user_id', Auth::id())->get();
        $cartMap = [];

        foreach ($dbItems as $dbItem) {
            $cartMap[$dbItem->product_id] = ['qty' => (int) $dbItem->quantity];
        }

        session([self::SESSION_KEY => $cartMap]);
    }

    /**
     * Helper to load items from DB when authenticated.
     */
    protected function getItemsFromDatabase(): array
    {
        $dbItems = CartItem::with(['product.primaryImage', 'product.category'])
            ->where('user_id', Auth::id())
            ->get();

        $items = [];
        foreach ($dbItems as $dbItem) {
            $product = $dbItem->product;
            if (!$product) {
                $dbItem->delete();
                continue;
            }

            $salePrice = $product->promo_price && $product->is_promo
                ? (int) $product->promo_price
                : (int) $product->price;

            $items[] = [
                'id'             => $product->id,
                'name'           => $product->name,
                'brand'          => $product->brand ?? '',
                'category'       => $product->category?->name ?? '',
                'image'          => $product->image_url,
                'unit_price'     => $salePrice,
                'original_price' => (int) $product->price,
                'on_sale'        => (bool) ($product->is_promo && $product->promo_price),
                'quantity'       => (int) $dbItem->quantity,
                'stock'          => (int) $product->stock,
                'status'         => $product->status,
            ];
        }

        return $items;
    }

    /**
     * Helper to load items from session when guest.
     */
    protected function getItemsFromSession(): array
    {
        $cart = session(self::SESSION_KEY, []);

        if (empty($cart)) {
            return [];
        }

        $productIds = array_keys($cart);
        $products = Product::with(['primaryImage', 'category'])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $items = [];
        foreach ($cart as $productId => $cartData) {
            $product = $products->get($productId);
            if (!$product) {
                $this->removeItem($productId);
                continue;
            }

            $salePrice = $product->promo_price && $product->is_promo
                ? (int) $product->promo_price
                : (int) $product->price;

            $items[] = [
                'id'             => $product->id,
                'name'           => $product->name,
                'brand'          => $product->brand ?? '',
                'category'       => $product->category?->name ?? '',
                'image'          => $product->image_url,
                'unit_price'     => $salePrice,
                'original_price' => (int) $product->price,
                'on_sale'        => (bool) ($product->is_promo && $product->promo_price),
                'quantity'       => (int) ($cartData['qty'] ?? 1),
                'stock'          => (int) $product->stock,
                'status'         => $product->status,
            ];
        }

        return $items;
    }
}
