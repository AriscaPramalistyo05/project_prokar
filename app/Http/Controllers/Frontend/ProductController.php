<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Halaman katalog produk
     */
    public function index(): View
    {
        $categories = Category::all();

        return view('pages.products', compact('categories'));
    }

    /**
     * Halaman detail produk
     */
    public function show(string $slug): View
    {
        $product = Product::with(['category', 'productImages', 'primaryImage'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Produk serupa: dari kategori yang sama, kecuali produk ini sendiri
        $relatedProducts = Product::with('primaryImage')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->available()
            ->take(4)
            ->get();

        return view('pages.product-detail', compact('product', 'relatedProducts'));
    }
}
