<?php

namespace App\Support;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Logika chatbot "Tanya Afa": instruksi untuk model, katalog ringkas, dan batas pemakaian.
 */
class Chatbot
{
    public const CATALOG_CACHE_KEY = 'chatbot_catalog';

    public const DEFAULT_DAILY_LIMIT = 100;

    public const DEFAULT_VISITOR_LIMIT = 20;

    /** Skema jawaban: hanya teks + ID produk, kartu produk disusun server agar hemat token output. */
    public const RESPONSE_SCHEMA = [
        'type' => 'OBJECT',
        'properties' => [
            'reply' => ['type' => 'STRING'],
            'product_ids' => ['type' => 'ARRAY', 'items' => ['type' => 'INTEGER']],
            'handoff' => ['type' => 'BOOLEAN'],
        ],
        'required' => ['reply'],
    ];

    public static function enabled(): bool
    {
        return (bool) Setting::getValue('chatbot_enabled', false) && filled(config('services.gemini.key'));
    }

    public static function dailyLimit(): int
    {
        return max(0, (int) Setting::getValue('chatbot_daily_limit', self::DEFAULT_DAILY_LIMIT));
    }

    public static function visitorLimit(): int
    {
        return max(0, (int) Setting::getValue('chatbot_visitor_limit', self::DEFAULT_VISITOR_LIMIT));
    }

    /**
     * Naikkan penghitung harian; false bila batas pengunjung atau batas total sudah tercapai.
     * Pengunjung dikenali dari hash IP + APP_KEY, IP-nya sendiri tidak disimpan.
     */
    public static function consumeQuota(string $ip): bool
    {
        $day = now()->format('Y-m-d');
        $globalKey = "chatbot:total:{$day}";
        $visitorKey = "chatbot:visitor:{$day}:" . hash_hmac('sha256', $ip, (string) config('app.key'));

        if ((int) Cache::get($globalKey, 0) >= self::dailyLimit() || (int) Cache::get($visitorKey, 0) >= self::visitorLimit()) {
            return false;
        }

        foreach ([$globalKey, $visitorKey] as $key) {
            Cache::add($key, 0, now()->endOfDay());
            Cache::increment($key);
        }

        return true;
    }

    public static function usedToday(): int
    {
        return (int) Cache::get('chatbot:total:' . now()->format('Y-m-d'), 0);
    }

    public static function systemPrompt(): string
    {
        $s = Setting::allCached();
        $site = $s['site_name'] ?? 'Afa Xuping Accessories';

        $info = collect([
            'Alamat' => $s['contact_address'] ?? null,
            'Jam layanan' => $s['opening_hours'] ?? null,
            'Tentang' => isset($s['about_content']) ? Str::limit(strip_tags($s['about_content']), 300) : null,
        ])->filter()->map(fn ($v, $k) => "{$k}: {$v}")->implode("\n");

        $catalog = self::catalog();

        return <<<PROMPT
        Kamu "Afa", asisten toko perhiasan Xuping {$site}. Bahasa Indonesia santai & sopan, sapa "Kak", jawab singkat maksimal 3 kalimat, boleh 1 emoji.
        Aturan:
        - Hanya bahas toko, perhiasan, dan produk di daftar. Tolak topik lain dengan ramah.
        - Harga, stok, warna, ukuran HANYA dari daftar. Jangan mengarang produk, diskon, atau ongkir.
        - Rekomendasi: isi product_ids (maks 3, hanya id dari daftar, utamakan yang Tersedia). Jangan tulis link atau mengulang daftar harga panjang di reply.
        - Cara pesan: buka produk, pilih warna/ukuran, tekan "Tambah ke Pesanan", lalu kirim via WhatsApp. Tanpa daftar akun.
        - Ongkir, pembayaran, custom, komplain, atau bila tidak yakin: arahkan ke admin WhatsApp dan set handoff=true.
        - Jangan meminta data pribadi.
        {$info}
        Produk (id|nama|kategori|harga Rp|status|warna|ukuran):
        {$catalog}
        PROMPT;
    }

    /**
     * Katalog satu baris per produk, di-cache dan dibersihkan otomatis saat produk berubah.
     */
    public static function catalog(): string
    {
        return Cache::remember(self::CATALOG_CACHE_KEY, now()->addHour(), function () {
            return Product::active()->with('category')
                ->orderByDesc('is_featured')->orderBy('sort_order')->latest()
                ->take(80)->get()
                ->map(fn (Product $p) => implode('|', [
                    $p->id,
                    $p->name,
                    $p->category?->name ?? '-',
                    number_format($p->price, 0, '', '') . ($p->has_discount ? ' (normal ' . number_format($p->compare_price, 0, '', '') . ')' : ''),
                    $p->in_stock ? 'Tersedia' : 'Habis',
                    $p->colors ? implode(',', $p->colors) : '-',
                    $p->sizes ? implode(',', $p->sizes) : '-',
                ]))
                ->implode("\n") ?: '(belum ada produk)';
        });
    }

    /**
     * Ubah ID produk dari model menjadi kartu produk; ID yang tidak dikenal dibuang.
     */
    public static function productCards(array $ids): array
    {
        $ids = collect($ids)->map(fn ($id) => (int) $id)->filter()->unique()->take(3)->values();

        if ($ids->isEmpty()) {
            return [];
        }

        $products = Product::active()->with('images')->whereIn('id', $ids)->get()->keyBy('id');

        return $ids->map(fn ($id) => $products->get($id))->filter()->map(fn (Product $p) => $p->cartPayload() + [
            'in_stock' => $p->in_stock,
            'has_variants' => $p->has_variants,
            'compare_price' => $p->has_discount ? (float) $p->compare_price : null,
        ])->values()->all();
    }
}
