<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Support\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $status = $request->input('status');
        $categoryId = $request->input('category_id');
        $stock = $request->input('stock');

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

        if ($stock === 'out') {
            $query->where('stock', '<=', 0);
        } elseif ($stock === 'low') {
            $query->whereBetween('stock', [1, 5]);
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
        $product = new Product(['is_active' => true, 'stock' => 0]);

        return view('dashboard.products.create', compact('categories', 'product'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $product = Product::create($data + [
            'slug' => Str::slug($request->name) . '-' . time(),
            'user_id' => auth()->id(),
            'created_by' => auth()->id(),
        ]);

        $this->storeImages($request, $product);

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
        $data = $this->validated($request, [
            'deleted_image_ids' => 'nullable|array',
            'deleted_image_ids.*' => 'exists:product_images,id',
            'primary_image_id' => 'nullable|exists:product_images,id',
        ]);

        $product->update($data);

        if ($request->has('deleted_image_ids')) {
            $imagesToDelete = ProductImage::whereIn('id', $request->deleted_image_ids)
                ->where('product_id', $product->id)
                ->get();

            foreach ($imagesToDelete as $img) {
                Storage::disk('public')->delete($img->image_path);
                $img->delete();
            }
        }

        if ($request->filled('primary_image_id')) {
            $primaryImg = ProductImage::where('id', $request->primary_image_id)
                ->where('product_id', $product->id)
                ->first();

            if ($primaryImg) {
                ProductImage::where('product_id', $product->id)->update(['is_primary' => false]);
                $primaryImg->update(['is_primary' => true]);
            }
        }

        $this->storeImages($request, $product);

        // Pastikan selalu ada satu gambar utama
        if (! $product->images()->where('is_primary', true)->exists()) {
            $product->images()->first()?->update(['is_primary' => true]);
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

    public function updateStock(Request $request, Product $product)
    {
        $request->validate(['stock' => 'required|integer|min:0|max:999999']);

        $product->update(['stock' => $request->integer('stock')]);

        return response()->json(['success' => true, 'stock' => $product->stock]);
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

    private function validated(Request $request, array $extraRules = []): array
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|gt:price',
            'stock' => 'required|integer|min:0|max:999999',
            'sizes' => 'nullable|string|max:500',
            'colors' => 'nullable|array',
            'colors.*' => Rule::in(array_keys(Product::COLORS)),
            'badge' => ['nullable', Rule::in(array_keys(Product::BADGES))],
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable',
            'is_featured' => 'nullable',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ] + $extraRules, [
            'compare_price.gt' => 'Harga coret harus lebih besar dari harga jual.',
            'images.*.max' => 'Ukuran setiap foto maksimal 5 MB.',
        ]);

        return [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'compare_price' => $request->filled('compare_price') ? $request->compare_price : null,
            'stock' => $request->integer('stock'),
            'sizes' => $this->parseSizes($request->input('sizes')),
            'colors' => array_values(array_intersect(array_keys(Product::COLORS), $request->input('colors', []))) ?: null,
            'badge' => $request->input('badge') ?: null,
            'sort_order' => $request->integer('sort_order'),
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
        ];
    }

    /**
     * "6, 7, 8" atau "40 cm; 45 cm" menjadi ['6', '7', '8'].
     */
    private function parseSizes(?string $sizes): ?array
    {
        $list = collect(preg_split('/[,;\n]+/', (string) $sizes))
            ->map(fn ($s) => trim($s))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return $list ?: null;
    }

    private function storeImages(Request $request, Product $product): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $hasPrimary = $product->images()->where('is_primary', true)->exists();
        $nextOrder = (int) $product->images()->max('sort_order') + 1;

        foreach ($request->file('images') as $index => $image) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => ImageUploader::store($image, 'products'),
                'is_primary' => ! $hasPrimary && $index === 0,
                'sort_order' => $nextOrder + $index,
            ]);
        }
    }
}
