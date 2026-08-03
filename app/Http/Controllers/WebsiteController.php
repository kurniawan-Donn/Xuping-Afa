<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
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
