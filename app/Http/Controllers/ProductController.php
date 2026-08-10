<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $status = $request->input('status');
        $categoryId = $request->input('category_id');

        $query = Product::with(['category', 'images']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        if ($status !== null && $status !== '') {
            $query->where('is_active', $status);
        }
        
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Urutkan yang aktif dulu, baru terbaru
        $query->orderBy('is_active', 'desc')->latest();

        $products = $query->paginate($perPage)->withQueryString();
        
        $categories = Category::orderBy('name')->get();

        if ($request->ajax()) {
            return view('dashboard.products._table', compact('products'))->render();
        }

        return view('dashboard.products.index', compact('products', 'categories', 'perPage'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('dashboard.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'nullable',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'is_active' => $request->boolean('is_active'),
            'user_id' => auth()->id(),
            'created_by' => auth()->id(),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images', 'creator']);
        return view('dashboard.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $product->load('images');
        return view('dashboard.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'nullable',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'deleted_image_ids' => 'nullable|array',
            'deleted_image_ids.*' => 'exists:product_images,id',
            'primary_image_id' => 'nullable|exists:product_images,id',
        ]);

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Process deletions
        if ($request->has('deleted_image_ids')) {
            $imagesToDelete = \App\Models\ProductImage::whereIn('id', $request->deleted_image_ids)
                ->where('product_id', $product->id)
                ->get();
                
            foreach ($imagesToDelete as $img) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($img->image_path);
                $img->delete();
            }
        }

        // Process primary image
        if ($request->filled('primary_image_id')) {
            $primaryImg = \App\Models\ProductImage::where('id', $request->primary_image_id)
                ->where('product_id', $product->id)
                ->first();
                
            if ($primaryImg) {
                \App\Models\ProductImage::where('product_id', $product->id)->update(['is_primary' => false]);
                $primaryImg->update(['is_primary' => true]);
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                $hasPrimary = $product->images()->where('is_primary', true)->exists();
                \App\Models\ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => !$hasPrimary && $index === 0,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function setPrimaryImage(ProductImage $image)
    {
        ProductImage::where('product_id', $image->product_id)->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
        
        return response()->json(['success' => true]);
    }

    public function deleteImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $productId = $image->product_id;
        $wasPrimary = $image->is_primary;
        
        $image->delete();
        
        if ($wasPrimary) {
            $nextImage = ProductImage::where('product_id', $productId)->first();
            if ($nextImage) {
                $nextImage->update(['is_primary' => true]);
            }
        }
        
        return response()->json(['success' => true]);
    }
}
