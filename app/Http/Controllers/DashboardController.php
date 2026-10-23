<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'active' => Product::active()->count(),
            'out_of_stock' => Product::active()->where('stock', '<=', 0)->count(),
            'categories' => Category::count(),
            'wa_clicks' => (int) Product::sum('wa_clicks'),
            'testimonials' => Testimonial::active()->count(),
        ];

        $topProducts = Product::with('images')->where('wa_clicks', '>', 0)
            ->orderByDesc('wa_clicks')->take(5)->get();

        $lowStock = Product::with('images')->active()->where('stock', '<=', 5)
            ->orderBy('stock')->take(6)->get();

        $latestProducts = Product::with(['images', 'category'])->latest()->take(5)->get();

        return view('dashboard.index', compact('stats', 'topProducts', 'lowStock', 'latestProducts'));
    }
}
