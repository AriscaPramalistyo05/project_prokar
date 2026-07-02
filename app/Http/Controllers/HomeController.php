<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $promoProducts = Product::with(['category', 'primaryImage'])
            ->promo()
            ->available()
            ->take(6)
            ->get();

        return view('pages.home', compact('promoProducts'));
    }
}
