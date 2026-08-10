<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('dashboard.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $keys = [
            'hero_title',
            'hero_subtitle',
            'about_title',
            'about_content',
            'contact_phone',
            'contact_email',
            'contact_address',
            'footer_text',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $request->input($key)]
                );
            }
        }

        // Hero Image
        if ($request->hasFile('hero_image')) {
            $file = $request->file('hero_image');

            // Pastikan upload berhasil
            if ($file->isValid()) {
                $heroImage = Setting::where('key', 'hero_image')->first();

                // Hapus gambar lama
                if ($heroImage && $heroImage->value) {
                    Storage::disk('public')->delete($heroImage->value);
                }

                // Simpan gambar baru
                $path = $file->store('settings', 'public');

                // Cek apakah benar-benar tersimpan
                if (!Storage::disk('public')->exists($path)) {
                    return redirect()
                        ->back()
                        ->with('error', 'Gagal menyimpan hero image.');
                }

                Setting::updateOrCreate(
                    ['key' => 'hero_image'],
                    [
                        'value' => $path,
                        'type' => 'image',
                    ]
                );
            }
        }

        // About Image
        if ($request->hasFile('about_image')) {
            $file = $request->file('about_image');

            if ($file->isValid()) {
                $aboutImage = Setting::where('key', 'about_image')->first();

                // Hapus gambar lama
                if ($aboutImage && $aboutImage->value) {
                    Storage::disk('public')->delete($aboutImage->value);
                }

                // Simpan gambar baru
                $path = $file->store('settings', 'public');

                // Cek apakah benar-benar tersimpan
                if (!Storage::disk('public')->exists($path)) {
                    return redirect()
                        ->back()
                        ->with('error', 'Gagal menyimpan about image.');
                }

                Setting::updateOrCreate(
                    ['key' => 'about_image'],
                    [
                        'value' => $path,
                        'type' => 'image',
                    ]
                );
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
