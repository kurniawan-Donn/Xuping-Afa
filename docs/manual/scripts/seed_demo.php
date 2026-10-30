<?php

/*
 * Mengisi database KHUSUS buku panduan (afa_xuping_manual) dengan data contoh.
 * Semua nama, email, dan nomor telepon di sini fiktif.
 *
 * Jalankan dari folder project (Git Bash):
 *   APP_ENV=local php artisan migrate:fresh --seed --force
 *   APP_ENV=local php docs/manual/scripts/seed_demo.php
 */

use App\Models\Banner;
use App\Models\Category;
use App\Models\ChatLog;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

$root = dirname(__DIR__, 3);
require $root . '/vendor/autoload.php';
$app = require $root . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Pengaman: skrip ini hanya boleh menyentuh database lokal khusus panduan.
$db = config('database.connections.mysql');
if ($db['database'] !== 'afa_xuping_manual' || ! in_array($db['host'], ['127.0.0.1', 'localhost'], true)) {
    fwrite(STDERR, "Dibatalkan: database bukan afa_xuping_manual di localhost.\n");
    exit(1);
}

// Salin foto contoh ke folder terpisah agar tidak tercampur dengan data development.
$src = public_path('storage');
$dst = public_path('storage/manual-demo');
File::ensureDirectoryExists($dst);
$photos = [
    'gelang-silver' => 'products/3z0sVU5hMYh0gA0MaUr2AxZDsGU50GDKXL2pnMH3.png',
    'gelang-bangle' => 'products/6D5yi7UWWcq5kH0gwLPK6jHyJ5N19Rr7EPEX6Q0z.jpg',
    'kalung-pelangi' => 'products/7ydXqp7jA4if0mmg8LSbJogbaY9DZDci055YHG3W.jpg',
    'cincin-solitaire' => 'products/9ei1YdiVJ6mLPzXc7domAYGjNsc9r7fhhb6vMSLc.jpg',
    'cincin-mahkota' => 'products/brmXb7ukomEAnnfFwp8OSgUqYoolNcKvqOaNuCQm.jpg',
    'cincin-bunga' => 'products/iLeRPddQwNCUwM7jgsYKldD5kSVm7miMxA20WwYQ.jpg',
    'kalung-kunci' => 'products/lV85MHd7j9SFg9Q3aCbPT8dExZd6uKeG38CWfZq9.jpg',
    'anting-juntai' => 'products/zSo1HDBKN5ETowp4jx9FsMjSsZLyzybRhLqkmxTF.jpg',
    'model-kalung' => 'settings/1YbMcbjvpDiYBcYwPeHQuH6HxUteVe0kzCKeobJ4.jpg',
    'flatlay' => 'settings/TNTAZpZxEC6CcCF05NbPgmh6Nbx7HVoOkveWuAjz.jpg',
];
$path = [];
foreach ($photos as $name => $file) {
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    File::copy("$src/$file", "$dst/$name.$ext");
    $path[$name] = "manual-demo/$name.$ext";
}

// Gambar banner versi HP (potret 4:5) dipotong dari foto model.
$img = imagecreatefromjpeg("$src/" . $photos['model-kalung']);
$h = imagesy($img);
$w = (int) round($h * 4 / 5);
$crop = imagecrop($img, ['x' => (int) ((imagesx($img) - $w) / 2), 'y' => 0, 'width' => $w, 'height' => $h]);
imagejpeg($crop, "$dst/banner-hp.jpg", 88);
$path['banner-hp'] = 'manual-demo/banner-hp.jpg';

// Akun (nama dan email fiktif). Kata sandi hanya untuk database lokal ini.
$password = Hash::make('PanduanAfa2026');
$accounts = [
    'superadmin@gmail.com' => ['Bagus Santoso', 'bagus.santoso@contoh.id'],
    'owner@gmail.com' => ['Siti Rahmawati', 'siti.rahmawati@contoh.id'],
    'admin@gmail.com' => ['Dewi Lestari', 'dewi.lestari@contoh.id'],
];
foreach ($accounts as $old => [$name, $email]) {
    User::where('email', $old)->update(['name' => $name, 'email' => $email, 'password' => $password]);
}
$admin2 = User::create(['name' => 'Fajar Nugroho', 'email' => 'fajar.nugroho@contoh.id', 'password' => $password, 'is_active' => false]);
$admin2->assignRole('admin');
$admin = User::where('email', 'dewi.lestari@contoh.id')->first();

$cat = [];
foreach ([
    'Cincin' => 'Cincin tunangan, cincin harian, dan cincin permata.',
    'Kalung' => 'Kalung rantai dan kalung liontin berbagai model.',
    'Gelang' => 'Gelang rantai dan gelang bangle.',
    'Anting' => 'Anting tusuk dan anting juntai.',
] as $name => $desc) {
    $cat[$name] = Category::create(['name' => $name, 'slug' => \Illuminate\Support\Str::slug($name), 'description' => $desc]);
}

$products = [
    ['Kalung Liontin Bunga Pelangi', 'Kalung', 'kalung-pelangi', 135000, null, 8, null, ['Gold'], 'bestseller', true, 41,
        "Kalung rantai lapis emas dengan liontin bunga dari batu zirkon warna-warni.\nPanjang rantai 45 cm dengan pengait lobster yang kuat."],
    ['Cincin Solitaire Permata Klasik', 'Cincin', 'cincin-solitaire', 95000, null, 15, ['6', '7', '8', '9'], ['Gold', 'Silver'], null, true, 33,
        "Cincin model solitaire dengan satu permata zirkon bening di tengah.\nCocok untuk hadiah tunangan atau dipakai sehari-hari."],
    ['Cincin Permata Mahkota', 'Cincin', 'cincin-mahkota', 110000, 130000, 2, ['6', '7', '8'], ['Gold'], 'promo', true, 27,
        "Cincin dengan susunan permata berbentuk mahkota.\nTermasuk kotak beludru merah, siap dijadikan kado."],
    ['Kalung Liontin Kunci Kupu-Kupu', 'Kalung', 'kalung-kunci', 129000, null, 7, ['40 cm', '45 cm'], ['Rose Gold'], 'new', true, 19,
        "Kalung rose gold dengan liontin kunci berhias kupu-kupu zirkon.\nTersedia dua pilihan panjang rantai."],
    ['Anting Juntai Kristal Rose Gold', 'Anting', 'anting-juntai', 79000, null, 10, null, ['Rose Gold'], 'bestseller', false, 15,
        "Anting juntai dengan deretan kristal kecil yang berkilau saat bergerak.\nMemakai pengait hook yang nyaman."],
    ['Gelang Bangle Ukir Rose Gold', 'Gelang', 'gelang-bangle', 125000, 150000, 5, null, ['Rose Gold', 'Gold'], 'promo', false, 12,
        "Gelang bangle dengan motif ukir halus di sepanjang permukaan.\nDiameter dalam 6 cm, pas untuk pergelangan dewasa."],
    ['Gelang Rantai Bunga Silver', 'Gelang', 'gelang-silver', 89000, null, 12, null, ['Silver'], 'new', false, 8,
        "Gelang rantai warna silver dengan hiasan bunga kecil.\nPanjang dapat diatur dari 16 sampai 20 cm."],
    ['Cincin Bunga Rose Gold', 'Cincin', 'cincin-bunga', 99000, null, 0, ['6', '7', '8'], ['Rose Gold'], null, false, 6,
        "Cincin dengan permata utama berbentuk bunga dan hiasan daun di sisi kanan dan kiri."],
];
foreach ($products as $i => [$name, $category, $photo, $price, $compare, $stock, $sizes, $colors, $badge, $featured, $clicks, $desc]) {
    $p = Product::create([
        'category_id' => $cat[$category]->id,
        'user_id' => $admin->id,
        'created_by' => $admin->id,
        'name' => $name,
        'slug' => \Illuminate\Support\Str::slug($name),
        'description' => $desc,
        'price' => $price,
        'compare_price' => $compare,
        'stock' => $stock,
        'sizes' => $sizes,
        'colors' => $colors,
        'badge' => $badge,
        'is_active' => true,
        'is_featured' => $featured,
        'sort_order' => $i,
        'wa_clicks' => $clicks,
    ]);
    ProductImage::create(['product_id' => $p->id, 'image_path' => $path[$photo], 'is_primary' => true, 'sort_order' => 0]);
    if ($photo === 'kalung-kunci') {
        ProductImage::create(['product_id' => $p->id, 'image_path' => $path['model-kalung'], 'is_primary' => false, 'sort_order' => 1]);
    }
}

Banner::create([
    'title' => 'Koleksi Rose Gold Terbaru', 'subtitle' => 'Kalung, cincin, dan anting lapis emas yang tahan lama.',
    'link_url' => '/products?category=kalung', 'image_path' => $path['model-kalung'], 'image_mobile_path' => $path['banner-hp'],
    'hide_text_mobile' => false, 'is_active' => true, 'sort_order' => 1, 'user_id' => $admin->id, 'created_by' => $admin->id,
]);
Banner::create([
    'title' => 'Promo Akhir Bulan', 'subtitle' => 'Potongan harga hingga 20% untuk produk pilihan.',
    'link_url' => null, 'image_path' => $path['flatlay'], 'is_active' => true, 'sort_order' => 2,
    'user_id' => $admin->id, 'created_by' => $admin->id,
]);

foreach ([
    ['Rina Wulandari', 'Madiun', 'Kalungnya cantik dan warnanya tidak pudar walau sudah dipakai sebulan. Pengiriman juga cepat.', 5],
    ['Ahmad Fauzi', 'Ponorogo', 'Beli cincin untuk lamaran, ukurannya pas dan kotaknya rapi. Admin ramah menjawab pertanyaan.', 5],
    ['Novi Andriani', 'Magetan', 'Antingnya ringan dan nyaman dipakai seharian. Harga terjangkau untuk kualitas seperti ini.', 5],
    ['Yuni Kartika', 'Ngawi', 'Gelangnya sesuai foto. Pemesanan lewat WhatsApp mudah dan dibantu sampai barang sampai.', 4],
] as $i => [$name, $loc, $content, $rating]) {
    Testimonial::create(['name' => $name, 'location' => $loc, 'content' => $content, 'rating' => $rating, 'is_active' => true, 'sort_order' => $i]);
}

$settings = [
    'site_name' => 'Afa Xuping Accessories',
    'hero_title' => 'Perhiasan Xuping Berkualitas, Harga Bersahabat',
    'hero_subtitle' => 'Koleksi lapis emas yang awet dan tidak mudah pudar.',
    'about_title' => 'Tentang Afa Xuping Accessories',
    'about_content' => "Afa Xuping Accessories adalah toko perhiasan Xuping di Kota Madiun.\nKami menyediakan cincin, kalung, gelang, dan anting lapis emas dengan model terbaru dan harga terjangkau.\nPemesanan dilayani melalui WhatsApp dan dapat dikirim ke seluruh Indonesia.",
    'whatsapp_number' => '081234567890',
    'whatsapp_greeting' => 'Halo Afa Xuping, saya ingin bertanya tentang produk.',
    'contact_phone' => '081234567890',
    'contact_email' => 'halo@contoh.id',
    'contact_address' => 'Jl. Pahlawan No. 12, Kartoharjo, Kota Madiun, Jawa Timur',
    'opening_hours' => 'Senin sampai Sabtu, 09.00 sampai 17.00 WIB',
    'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d31620!2d111.523!3d-7.629!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sid!2sid',
    'instagram_url' => 'https://www.instagram.com/contoh.afaxuping',
    'tiktok_url' => 'https://www.tiktok.com/@contoh.afaxuping',
    'facebook_url' => '',
    'shopee_url' => '',
    'meta_description' => 'Toko perhiasan Xuping di Madiun. Cincin, kalung, gelang, dan anting lapis emas. Pesan mudah lewat WhatsApp.',
    'footer_text' => 'Afa Xuping Accessories',
    'chatbot_enabled' => '1',
    'chatbot_daily_limit' => '300',
    'chatbot_visitor_limit' => '20',
];
foreach ($settings as $key => $value) {
    Setting::updateOrCreate(['key' => $key], ['value' => $value, 'type' => 'text']);
}
Setting::updateOrCreate(['key' => 'hero_image'], ['value' => $path['flatlay'], 'type' => 'image']);
Setting::updateOrCreate(['key' => 'about_image'], ['value' => $path['model-kalung'], 'type' => 'image']);

$ids = Product::pluck('id', 'slug');
foreach ([
    ['Anting yang ringan untuk dipakai sehari-hari ada?', 'Ada, Kak. Anting Juntai Kristal Rose Gold ringan dan nyaman dipakai seharian, harganya Rp79.000.', [$ids['anting-juntai-kristal-rose-gold']], 'ok', 26],
    ['Kalung rose gold yang ready apa saja?', 'Saat ini tersedia Kalung Liontin Kunci Kupu-Kupu warna rose gold, harganya Rp129.000.', [$ids['kalung-liontin-kunci-kupu-kupu']], 'ok', 29],
    ['Bisa kirim ke Surabaya?', 'Bisa, Kak. Pengiriman ke seluruh Indonesia. Untuk ongkos kirim, silakan hubungi admin lewat WhatsApp.', [], 'ok', 50],
] as [$q, $a, $pids, $status, $hoursAgo]) {
    ChatLog::create(['question' => $q, 'answer' => $a, 'product_ids' => $pids, 'status' => $status,
        'input_tokens' => 1450, 'output_tokens' => 60, 'created_at' => now()->subHours($hoursAgo), 'updated_at' => now()->subHours($hoursAgo)]);
}

echo "Data contoh selesai: " . Product::count() . " produk, " . User::count() . " akun.\n";
