<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Support\ImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    private const TEXT_KEYS = [
        'site_name',
        'hero_title',
        'hero_subtitle',
        'about_title',
        'about_content',
        'whatsapp_number',
        'whatsapp_greeting',
        'contact_phone',
        'contact_email',
        'contact_address',
        'opening_hours',
        'maps_embed_url',
        'instagram_url',
        'tiktok_url',
        'facebook_url',
        'shopee_url',
        'meta_description',
        'footer_text',
    ];

    private const IMAGE_KEYS = [
        'logo' => 600,
        'hero_image' => 1920,
        'about_image' => 1400,
        'og_image' => 1200,
    ];

    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('dashboard.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Kode <iframe> lengkap dari Google Maps boleh ditempel; ambil isi src-nya saja
        if (preg_match('/src=["\']([^"\']+)["\']/i', (string) $request->input('maps_embed_url'), $m)) {
            $request->merge(['maps_embed_url' => html_entity_decode($m[1])]);
        }

        $request->validate([
            'whatsapp_number' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'contact_email' => 'nullable|email|max:255',
            // Link berbagi (maps.app.goo.gl) ditolak Google bila ditampilkan di dalam iframe
            'maps_embed_url' => ['nullable', 'url', 'max:2000', 'regex:#^https://(www\.)?google\.[a-z.]+/maps/embed#i'],
            'instagram_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'shopee_url' => 'nullable|url|max:255',
            'meta_description' => 'nullable|string|max:300',
        ] + array_map(fn () => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', self::IMAGE_KEYS), [
            'whatsapp_number.regex' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'maps_embed_url.regex' => 'Gunakan link embed Google Maps (diawali https://www.google.com/maps/embed). Link berbagi seperti maps.app.goo.gl tidak bisa ditampilkan di website. Di Google Maps: Bagikan → Sematkan peta → Salin HTML, lalu tempel di sini.',
        ]);

        foreach (self::TEXT_KEYS as $key) {
            if ($request->has($key)) {
                Setting::updateOrCreate(['key' => $key], ['value' => $request->input($key)]);
            }
        }

        foreach (self::IMAGE_KEYS as $key => $maxWidth) {
            if (! $request->hasFile($key) || ! $request->file($key)->isValid()) {
                continue;
            }

            $old = Setting::where('key', $key)->value('value');
            if ($old) {
                Storage::disk('public')->delete($old);
            }

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => ImageUploader::store($request->file($key), 'settings', $maxWidth), 'type' => 'image']
            );
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
