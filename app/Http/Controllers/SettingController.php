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

    private const MAPS_EMBED_PATTERN = '#https://(www\.)?google\.[a-z.]+/maps/embed\?[^"\'\s<>]+#i';

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
        $mapsInput = trim(html_entity_decode((string) $request->input('maps_embed_url'), ENT_QUOTES | ENT_HTML5));

        if (preg_match(self::MAPS_EMBED_PATTERN, $mapsInput, $m)) {
            // Ambil link embed dari mana pun di teks (kode <iframe> lengkap atau link saja)
            $request->merge(['maps_embed_url' => $m[0]]);
        } elseif ($mapsInput !== '' && $mapsInput === Setting::where('key', 'maps_embed_url')->value('value')) {
            // Link lama yang tidak valid & tidak diubah: kosongkan, jangan halangi penyimpanan kolom lain
            $request->merge(['maps_embed_url' => null]);
        }

        $request->validate([
            'whatsapp_number' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'contact_email' => 'nullable|email|max:255',
            // Link berbagi (maps.app.goo.gl) ditolak Google bila ditampilkan di dalam iframe
            'maps_embed_url' => ['nullable', 'string', 'max:2000', 'regex:' . self::MAPS_EMBED_PATTERN],
            'instagram_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'shopee_url' => 'nullable|url|max:255',
            'meta_description' => 'nullable|string|max:300',
        ] + array_map(fn () => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', self::IMAGE_KEYS), [
            'whatsapp_number.regex' => 'Nomor WhatsApp hanya boleh berisi angka.',
            'maps_embed_url.regex' => 'Link Google Maps belum benar. Yang diterima: "' . \Illuminate\Support\Str::limit($mapsInput, 60) . '". Di Google Maps: Bagikan → Sematkan peta → Salin HTML, lalu tempel di sini (link berbagi maps.app.goo.gl tidak bisa dipakai).',
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
