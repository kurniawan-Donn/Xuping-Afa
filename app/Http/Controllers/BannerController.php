<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Support\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->latest()->get();

        return view('dashboard.banners.index', compact('banners'));
    }

    public function create()
    {
        $banner = new Banner(['is_active' => true]);

        return view('dashboard.banners.form', compact('banner'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['image_path'] = ImageUploader::store($request->file('image'), 'banners', 1920);
        $data['user_id'] = auth()->id();
        $data['created_by'] = auth()->id();

        Banner::create($data);

        return redirect()->route('banners.index')->with('success', 'Banner berhasil ditambahkan.');
    }

    public function edit(Banner $banner)
    {
        return view('dashboard.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $this->validated($request, false);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($banner->image_path);
            $data['image_path'] = ImageUploader::store($request->file('image'), 'banners', 1920);
        }

        $banner->update($data);

        return redirect()->route('banners.index')->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroy(Banner $banner)
    {
        Storage::disk('public')->delete($banner->image_path);
        $banner->delete();

        return redirect()->route('banners.index')->with('success', 'Banner berhasil dihapus.');
    }

    private function validated(Request $request, bool $imageRequired): array
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'link_url' => ['nullable', 'string', 'max:255', 'regex:/^(\/|https?:\/\/)/i'],
            'sort_order' => 'nullable|integer',
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ], [
            'link_url.regex' => 'Link harus diawali "/" (halaman di website ini) atau "https://".',
        ]);

        return [
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'link_url' => $request->link_url,
            'sort_order' => $request->integer('sort_order'),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
