# Deploy ke InfinityFree

InfinityFree tidak punya SSH, Composer, Node, maupun cron. Semua perintah build dijalankan di komputer lokal, hasilnya di-upload.

## 1. Siapkan paket di lokal

```bash
git checkout main
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan view:clear && php artisan config:clear
```

`composer install --no-dev` penting supaya folder `vendor` tidak membawa paket testing (hemat ribuan file / inode).

## 2. Database

Server InfinityFree tidak bisa menjalankan `php artisan migrate`. Data utama ada di database lokal, jadi:

1. Di phpMyAdmin **lokal** (Laragon): pilih database `afa_xuping` → **Export** → format SQL.
2. Di phpMyAdmin **InfinityFree**: backup dulu (Export), lalu kosongkan tabel lama dan **Import** file SQL tadi.

## 3. Upload file

1. Backup folder `htdocs` lama (terutama `public/storage` jika ada foto yang belum ada di lokal).
2. Kompres proyek menjadi ZIP **tanpa** `node_modules`, `.git`, `tests`, `.env`, `.env.production`.
3. Upload ZIP lewat File Manager ke `htdocs`, lalu **Extract**.
4. Upload `.env` produksi (lihat `.env.production` di lokal) dengan `APP_ENV=production` dan `APP_DEBUG=false`.

Foto upload tersimpan di `public/storage` (bukan symlink) karena InfinityFree tidak mendukung `storage:link`.

## 4. Setelah upload, cek

- `https://xuping-afa.my.id/.env` harus **403 Forbidden** (bukan menampilkan isi file).
- Beranda, katalog, detail produk, dan login admin terbuka normal.
- Isi nomor WhatsApp di **Dashboard → Website & WhatsApp**, lalu coba tombol pesan.
- Daftarkan `https://xuping-afa.my.id/sitemap.xml` di Google Search Console.
