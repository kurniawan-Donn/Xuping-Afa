<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

use App\Models\Setting;

class WebsiteController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $products = Product::active()->with('category')->latest()->take(8)->get();
        return view('website.index', compact('settings', 'products'));
    }

    public function about()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('website.about', compact('settings'));
    }

    public function productIndex()
    {
        $categories = Category::orderBy('name')->get();
        $products = Product::active()->with('category')
            ->when(request('category'), function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })->latest()->paginate(12)->withQueryString();
        return view('website.products.index', compact('products', 'categories'));
    }
}
