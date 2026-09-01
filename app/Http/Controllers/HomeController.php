<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View|RedirectResponse
    {
        // Auto-redirect Super Admin to Admin Dashboard when opening the app / homepage
        if (auth()->check() && auth()->user()->hasRole('super_admin') && !request()->has('view_as_guest')) {
            return redirect()->route('admin.dashboard');
        }

        $promoProducts = Product::with(['category', 'primaryImage'])
            ->promo()
            ->available()
            ->take(6)
            ->get();

        if ($promoProducts->isEmpty()) {
            $promoProducts = Product::with(['category', 'primaryImage'])
                ->available()
                ->take(6)
                ->get();
        }

        return view('pages.home', compact('promoProducts'));
    }
}
