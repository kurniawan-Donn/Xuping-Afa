<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index()
    {
        $banners = Banner::active()->orderBy('sort_order')->latest()->get();

        $featured = Product::active()->with(['category', 'images'])
            ->orderByDesc('is_featured')->orderBy('sort_order')->latest()
            ->take(8)->get();

        $newArrivals = Product::active()->with(['category', 'images'])->latest()->take(4)->get();

        $categories = Category::withCount(['products' => fn ($q) => $q->active()])
            ->orderBy('name')->get()
            ->where('products_count', '>', 0)->values();

        $testimonials = Testimonial::active()->orderBy('sort_order')->latest()->take(9)->get();

        return view('website.index', compact('banners', 'featured', 'newArrivals', 'categories', 'testimonials'));
    }

    public function about()
    {
        $testimonials = Testimonial::active()->orderBy('sort_order')->latest()->take(3)->get();

        return view('website.about', compact('testimonials'));
    }

    public function productIndex(Request $request)
    {
        $categories = Category::withCount(['products' => fn ($q) => $q->active()])->orderBy('name')->get();

        $products = Product::active()->with(['category', 'images'])
            ->when($request->category, function ($query, $category) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $category)->orWhere('id', $category));
            })
            ->when($request->search, function ($query, $search) {
                $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%"));
            })
            ->when($request->min_price, fn ($q, $min) => $q->where('price', '>=', (int) $min))
            ->when($request->max_price, fn ($q, $max) => $q->where('price', '<=', (int) $max))
            ->when($request->boolean('available'), fn ($q) => $q->inStock());

        match ($request->sort) {
            'price_asc' => $products->orderBy('price'),
            'price_desc' => $products->orderByDesc('price'),
            'popular' => $products->orderByDesc('wa_clicks'),
            default => $products->orderByDesc('is_featured')->orderBy('sort_order')->latest(),
        };

        $products = $products->paginate(12)->withQueryString();

        if ($request->ajax()) {
            return view('website.products._grid', compact('products'))->render();
        }

        return view('website.products.index', compact('products', 'categories'));
    }

    public function productShow(Product $product)
    {
        if (! $product->is_active) {
            abort(404);
        }

        $product->load('images', 'category');

        $related = Product::active()->with(['category', 'images'])
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->inRandomOrder()->take(4)->get();

        return view('website.products.show', compact('product', 'related'));
    }

    /**
     * Dipanggil dari browser setiap kali pengunjung mengirim pesanan WhatsApp,
     * untuk statistik produk paling diminati di dashboard.
     */
    public function waClick(Request $request)
    {
        $ids = collect($request->input('ids', []))->map(fn ($id) => (int) $id)->filter()->unique()->take(50);

        if ($ids->isNotEmpty()) {
            Product::whereIn('id', $ids)->increment('wa_clicks');
        }

        return response()->noContent();
    }

    public function sitemap()
    {
        $products = Product::active()->latest('updated_at')->get(['slug', 'updated_at']);

        return response()
            ->view('website.sitemap', compact('products'))
            ->header('Content-Type', 'application/xml');
    }
}
