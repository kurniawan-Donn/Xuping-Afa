"""
Mengambil screenshot buku panduan dari website lokal (database afa_xuping_manual).

Persiapan (Git Bash, dari folder project):
  1. APP_ENV=local php artisan migrate:fresh --seed --force
  2. APP_ENV=local php docs/manual/scripts/seed_demo.php
  3. cd public && APP_ENV=local php -S 127.0.0.1:8001 ../vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php

Lalu:  python docs/manual/scripts/screenshots.py [awalan-nama ...]
Tanpa argumen semua gambar diambil; dengan argumen hanya gambar yang namanya diawali awalan tersebut.
"""

import re
import sys
from pathlib import Path

from playwright.sync_api import sync_playwright

BASE = 'http://127.0.0.1:8001'
OUT = Path(__file__).resolve().parent.parent / 'screenshots'
PASSWORD = 'PanduanAfa2026'  # hanya berlaku di database lokal panduan
ACCOUNTS = {
    'superadmin': 'bagus.santoso@contoh.id',
    'owner': 'siti.rahmawati@contoh.id',
    'admin': 'dewi.lestari@contoh.id',
}
DESKTOP = {'width': 1366, 'height': 768}
MOBILE = {'width': 390, 'height': 844}
RED = '#E53935'

# Menggambar kotak merah 3px di atas elemen target. Kotak dibuat terpisah dari elemen
# agar tidak terpotong oleh overflow:hidden milik elemen induknya.
DRAW_JS = """(boxes) => {
    document.querySelectorAll('.manual-mark').forEach(e => e.remove());
    for (const b of boxes) {
        const d = document.createElement('div');
        d.className = 'manual-mark';
        Object.assign(d.style, {
            position: 'fixed', left: (b.x - 4) + 'px', top: (b.y - 4) + 'px',
            width: (b.width + 8) + 'px', height: (b.height + 8) + 'px',
            border: '3px solid %s', borderRadius: '8px', boxSizing: 'border-box',
            pointerEvents: 'none', zIndex: 2147483647,
        });
        document.body.appendChild(d);
    }
}""" % RED

CALM_CSS = """
*, *::before, *::after { transition: none !important; animation: none !important; caret-color: transparent !important; }
"""
# Tombol melayang (Tanya Afa dan WhatsApp) disembunyikan kecuali pada gambar yang membahasnya.
HIDE_FLOATS_CSS = """
[x-data="chatbot"] > button.fixed, a.fixed[aria-label="Chat WhatsApp"] { visibility: hidden !important; }
"""
SHOW_FLOATS = {'03-01_beranda', '03-13_tanya-afa', '03-13m_tanya-afa-hp', '03-01m_beranda-hp', '03-12_tombol-tanya-afa', '03-14m_navigasi-bawah-hp'}
state = {'floats': False}


def closest(loc, css):
    return vis(loc).evaluate_handle('(e, s) => e.closest(s)', css).as_element()


def vis(t):
    """Locator -> elemen pertama yang terlihat (halaman punya versi desktop dan HP yang disembunyikan)."""
    return t.filter(visible=True).first if hasattr(t, 'filter') else t


def mark(page, *targets, center=True):
    targets = [vis(t) for t in targets if t is not None]
    if center and targets:
        t = targets[0]
        t.evaluate("e => e.scrollIntoView({block: 'center'})")
        page.wait_for_timeout(400)
    boxes = []
    for t in targets:
        box = t.bounding_box()
        if box:
            boxes.append(box)
    page.evaluate(DRAW_JS, boxes)


def settle(page, ms=600):
    page.wait_for_load_state('networkidle')
    page.add_style_tag(content=CALM_CSS)
    if not state['floats']:
        page.add_style_tag(content=HIDE_FLOATS_CSS)
    page.wait_for_timeout(ms)


def go(page, url):
    page.goto(BASE + url)
    settle(page)


def login(page, role):
    page.goto(BASE + '/login')
    page.fill('#email', ACCOUNTS[role])
    page.fill('#password', PASSWORD)
    page.click('button[type=submit]')
    page.wait_for_url(re.compile(r'/dashboard'))


def section_of(page, heading):
    return closest(page.get_by_role('heading', name=heading, exact=True), 'section')


def dash_card(page, heading):
    return closest(page.get_by_text(heading, exact=True), '.card, section')


# ------------------------------------------------------------------ Bab 2: Memulai

def s_login(page):
    go(page, '/login')
    page.fill('#email', ACCOUNTS['admin'])
    page.fill('#password', PASSWORD)
    mark(page, page.locator('form[action$="/login"]'))


def s_login_gagal(page):
    go(page, '/login')
    page.fill('#email', ACCOUNTS['admin'])
    page.fill('#password', 'katasandisalah')
    page.click('button[type=submit]')
    settle(page)
    page.wait_for_selector('.swal2-popup')
    mark(page, page.locator('.swal2-popup'), center=False)


def s_login_nonaktif(page):
    go(page, '/login')
    page.fill('#email', 'fajar.nugroho@contoh.id')
    page.fill('#password', PASSWORD)
    page.click('button[type=submit]')
    settle(page)
    page.wait_for_selector('.swal2-popup')
    mark(page, page.locator('.swal2-popup'), center=False)


def s_tata_letak(page):
    go(page, '/dashboard')
    mark(page, page.locator('aside'), page.locator('header').first, center=False)


def s_menu_akun(page):
    go(page, '/dashboard')
    page.locator('header button[aria-expanded]').click()
    page.wait_for_timeout(400)
    keluar = page.get_by_role('button', name='Keluar')
    mark(page, closest(keluar, 'div[x-show]') or keluar, center=False)


# ------------------------------------------------------------------ Bab 3: Pengunjung

def s_beranda(page):
    go(page, '/')
    mark(page, page.locator('main section').first, center=False)


def s_beranda_kategori(page):
    go(page, '/')
    mark(page, section_of(page, 'Temukan gaya Anda'))


def s_beranda_unggulan(page):
    go(page, '/')
    mark(page, section_of(page, 'Paling dicari minggu ini'))


def s_pencarian(page):
    go(page, '/')
    page.get_by_role('button', name='Cari produk').first.click()
    page.wait_for_timeout(300)
    box = page.locator('input[name=search]').first
    box.fill('cincin')
    mark(page, box, center=False)


def s_katalog(page):
    go(page, '/products')
    mark(page, closest(page.get_by_role('button', name='Semua'), 'div'), page.locator('main select').first, center=False)


def filter_button(page):
    # Di HP tombol Filter hanya berupa ikon, letaknya tepat setelah pilihan urutan.
    return vis(page.locator(r'main button.btn-outline.lg\:hidden'))


def s_katalog_hp(page):
    go(page, '/products')
    mark(page, filter_button(page), center=False)


def s_katalog_filter_hp(page):
    go(page, '/products')
    filter_button(page).click()
    page.wait_for_timeout(500)
    panel = closest(page.get_by_role('button', name='Tampilkan hasil'), 'aside')
    mark(page, panel, center=False)


def s_detail(page):
    go(page, '/products/kalung-liontin-kunci-kupu-kupu')
    page.get_by_role('button', name='45 cm').click()
    mark(page, page.get_by_role('button', name='Rose Gold'), page.get_by_role('button', name='45 cm'),
         page.get_by_role('button', name='40 cm'), center=False)


def s_detail_tombol(page):
    go(page, '/products/kalung-liontin-kunci-kupu-kupu')
    vis(page.get_by_role('button', name='45 cm')).click()
    mark(page, page.get_by_role('button', name='Tambah ke Pesanan'), page.get_by_role('button', name='Pesan Sekarang'))


def s_detail_tombol_hp(page):
    go(page, '/products/kalung-liontin-kunci-kupu-kupu')
    vis(page.get_by_role('button', name='45 cm')).click()
    pesan = page.get_by_role('button', name='Pesan', exact=True)
    bar = closest(pesan, 'div.fixed')
    mark(page, bar or pesan, center=False)


def s_detail_hp(page):
    go(page, '/products/kalung-liontin-kunci-kupu-kupu')
    mark(page, page.get_by_role('heading', level=1), center=False)


def s_daftar_pesanan(page):
    for slug, size in [('cincin-solitaire-permata-klasik', '7'), ('kalung-liontin-kunci-kupu-kupu', '45 cm')]:
        go(page, '/products/' + slug)
        vis(page.get_by_role('button', name=size, exact=True)).click()
        colors = page.get_by_role('button', name='Gold', exact=True).filter(visible=True)
        if colors.count():
            colors.first.click()
        vis(page.locator('button[\\@click="addToCart()"]')).click()
        page.wait_for_timeout(500)
    drawer = page.locator('[role=dialog][aria-label="Daftar Pesanan"]')
    if not drawer.is_visible():
        vis(page.get_by_role('button', name=re.compile('Buka Daftar Pesanan|Pesanan'))).click()
    page.wait_for_timeout(400)
    toggle = drawer.get_by_role('button', name='Data pengiriman (opsional)')
    if not drawer.get_by_placeholder('Nama penerima').is_visible():
        toggle.click()
        page.wait_for_timeout(300)
    drawer.get_by_placeholder('Nama penerima').fill('Rina Wulandari')
    drawer.get_by_placeholder('Alamat lengkap').fill('Jl. Diponegoro No. 5, Kota Madiun')
    drawer.get_by_placeholder('Catatan (misal: untuk kado)').fill('Untuk kado ulang tahun')
    page.add_style_tag(content=CALM_CSS)
    page.wait_for_timeout(3500)  # tunggu notifikasi "Ditambahkan" hilang
    mark(page, drawer.get_by_role('button', name='Kirim Pesanan via WhatsApp'), center=False)


def s_tentang(page):
    go(page, '/about')
    head = page.get_by_role('heading', name='Mari terhubung', exact=True)
    head.evaluate("e => e.scrollIntoView({block: 'start'})")
    page.wait_for_timeout(2500)
    mark(page, closest(head, '.card, div.rounded-3xl, section'))


def s_tentang_atas(page):
    go(page, '/about')
    mark(page, page.get_by_role('heading', level=1), center=False)


def s_tanya_afa(page):
    go(page, '/')
    page.get_by_role('button', name='Tanya Afa').first.click()
    page.wait_for_timeout(400)
    chat = page.locator('section[aria-label="Chat Tanya Afa"]')
    chat.locator('textarea').fill('Ada cincin untuk lamaran yang harganya di bawah 100 ribu?')
    chat.get_by_role('button', name='Kirim').click()
    page.wait_for_timeout(1500)
    page.locator('[aria-label="Afa sedang mengetik"]').wait_for(state='hidden', timeout=30000)
    page.wait_for_timeout(800)
    mark(page, chat, center=False)


def s_tombol_tanya_afa(page):
    go(page, '/')
    mark(page, page.get_by_role('button', name='Tanya Afa').first, center=False)


def s_navigasi_bawah(page):
    go(page, '/')
    mark(page, page.locator('nav[aria-label="Navigasi bawah"]'), center=False)


# ------------------------------------------------------------------ Bab 4: Admin

def s_dashboard(page):
    go(page, '/dashboard')
    mark(page, closest(page.get_by_role('link', name=re.compile('^Produk aktif')), 'div.grid'), center=False)


def s_dashboard_bawah(page):
    go(page, '/dashboard')
    mark(page, dash_card(page, 'Paling diminati'), dash_card(page, 'Perlu restok'))


def s_produk(page):
    go(page, '/dashboard/products')
    mark(page, page.get_by_role('link', name='Tambah Produk'), center=False)


def s_produk_filter(page):
    go(page, '/dashboard/products')
    page.get_by_placeholder(re.compile('^Cari nama produk')).fill('cincin')
    page.wait_for_timeout(1200)
    mark(page, closest(page.get_by_placeholder(re.compile('^Cari nama produk')), 'form, div.card'), center=False)


def s_produk_stok(page):
    go(page, '/dashboard/products')
    row = page.locator('tr', has_text='Cincin Permata Mahkota')
    mark(page, row.locator('input[type=number]'))


def fill_product_form(page):
    page.fill('#name', 'Anting Tusuk Mutiara Klasik')
    page.select_option('#category_id', label='Anting')
    page.fill('#description', 'Anting tusuk dengan mutiara sintetis berdiameter 8 mm.\nRingan dan cocok untuk acara resmi.')
    page.set_input_files('input[name="images[]"]', [str(OUT.parent / 'scripts' / 'contoh-produk.jpg')])
    page.check('input[name="colors[]"][value="Gold"]', force=True)
    page.check('input[name="colors[]"][value="Silver"]', force=True)
    page.fill('#price', '65000')
    page.fill('#compare_price', '75000')
    page.fill('#stock', '20')
    page.select_option('#badge', label='Baru')
    page.wait_for_timeout(600)


def s_produk_form_info(page):
    go(page, '/dashboard/products/create')
    fill_product_form(page)
    mark(page, section_of(page, 'Informasi produk'))


def s_produk_form_foto(page):
    go(page, '/dashboard/products/create')
    fill_product_form(page)
    mark(page, section_of(page, 'Foto produk'))


def s_produk_form_varian(page):
    go(page, '/dashboard/products/create')
    fill_product_form(page)
    mark(page, section_of(page, 'Varian'))


def s_produk_form_harga(page):
    go(page, '/dashboard/products/create')
    fill_product_form(page)
    mark(page, section_of(page, 'Harga & stok'))


def s_produk_form_tampilan(page):
    go(page, '/dashboard/products/create')
    fill_product_form(page)
    mark(page, section_of(page, 'Tampilan di website'), page.get_by_role('button', name='Simpan Produk'))


def s_produk_validasi(page):
    go(page, '/dashboard/products/create')
    page.evaluate("document.querySelectorAll('[required]').forEach(e => e.removeAttribute('required'))")
    page.get_by_role('button', name='Simpan Produk').click()
    settle(page)
    page.wait_for_selector('.swal2-popup')
    page.locator('.swal2-confirm').click()
    page.wait_for_timeout(500)
    mark(page, closest(page.locator('#name'), 'div'), closest(page.locator('#price'), 'div'))


def product_id(page, name):
    go(page, '/dashboard/products')
    href = page.locator('tr', has_text=name).locator('a[href*="/edit"]').first.get_attribute('href')
    return href.rstrip('/').split('/')[-2]


def s_produk_edit_foto(page):
    pid = product_id(page, 'Kalung Liontin Kunci Kupu-Kupu')
    go(page, f'/dashboard/products/{pid}/edit')
    mark(page, section_of(page, 'Foto produk'))


def s_produk_detail(page):
    pid = product_id(page, 'Kalung Liontin Kunci Kupu-Kupu')
    go(page, f'/dashboard/products/{pid}')
    mark(page, page.get_by_role('heading', level=1), center=False)


def s_produk_hapus(page):
    go(page, '/dashboard/products')
    row = page.locator('tr', has_text='Gelang Rantai Bunga Silver')
    row.locator('form[data-confirm] button').first.click()
    page.wait_for_selector('.swal2-popup')
    page.wait_for_timeout(500)
    mark(page, page.locator('.swal2-popup'), center=False)


def s_kategori(page):
    go(page, '/dashboard/categories')
    mark(page, page.get_by_role('button', name='Tambah Kategori'), center=False)


def s_kategori_form(page):
    go(page, '/dashboard/categories')
    page.get_by_role('button', name='Tambah Kategori').click()
    page.wait_for_timeout(500)
    form = page.locator('[role=dialog] form')
    form.locator('input[type=text]').first.fill('Bros')
    ta = form.locator('textarea')
    if ta.count():
        ta.first.fill('Bros hijab dan bros kebaya.')
    mark(page, form, center=False)


def s_banner(page):
    go(page, '/dashboard/banners')
    mark(page, page.get_by_role('link', name='Tambah Banner'), center=False)


def banner_edit(page):
    go(page, '/dashboard/banners')
    page.locator('a[title=Edit]').first.click()
    settle(page)


def s_banner_desktop(page):
    banner_edit(page)
    mark(page, closest(page.get_by_text('Gambar desktop', exact=False).first, 'section'), center=False)


def s_banner_hp(page):
    banner_edit(page)
    mark(page, closest(page.locator('p', has_text='Gambar HP'), 'section'))


def s_banner_info(page):
    banner_edit(page)
    mark(page, closest(page.locator('#title'), 'section'), page.get_by_role('button', name='Simpan Banner'), center=False)


def s_testimoni(page):
    go(page, '/dashboard/testimonials')
    mark(page, page.get_by_role('link', name=re.compile('Tambah Testimoni')), center=False)


def s_testimoni_form(page):
    go(page, '/dashboard/testimonials/create')
    page.locator('input[name=name]').fill('Lina Marlina')
    loc = page.locator('input[name=location]')
    if loc.count():
        loc.fill('Caruban')
    page.locator('textarea[name=content]').fill('Cincinnya pas di jari dan warnanya tetap cerah. Terima kasih Afa Xuping.')
    mark(page, page.locator('form').filter(has=page.locator('textarea[name=content]')), center=False)


def s_profil(page):
    go(page, '/dashboard/profile')
    mark(page, page.get_by_role('link', name='Edit Profil'), center=False)


def s_profil_edit(page):
    go(page, '/dashboard/profile/edit')
    pw = page.locator('input[name=password]')
    mark(page, closest(pw, 'section, .card') if pw.count() else None)


# ------------------------------------------------------------------ Bab 5: Owner dan Superadmin

def s_pengguna(page):
    go(page, '/dashboard/users')
    mark(page, page.locator('table').first, center=False)


def s_pengguna_status(page):
    go(page, '/dashboard/users')
    row = page.locator('tr', has_text='Fajar Nugroho')
    mark(page, row.get_by_role('button').first)


def s_pengguna_form(page):
    go(page, '/dashboard/users/create')
    page.locator('input[name=name]').fill('Rudi Hartono')
    page.locator('input[name=email]').fill('rudi.hartono@contoh.id')
    sel = page.locator('select[name=role]')
    if sel.count():
        sel.select_option(label='Admin') if sel.locator('option', has_text='Admin').count() else None
    mark(page, sel if sel.count() else page.locator('form').last)


def s_pengguna_owner(page):
    go(page, '/dashboard/users')
    mark(page, page.locator('table').first, center=False)


def s_chatbot(page):
    go(page, '/dashboard/chatbot')
    mark(page, page.locator('main .card').first, center=False)


def settings_tab(name, heading=None):
    def shot(page):
        go(page, '/dashboard/settings')
        page.get_by_role('button', name=name, exact=True).click()
        page.wait_for_timeout(500)
        targets = [page.get_by_role('button', name=name, exact=True)]
        if heading:
            targets.append(dash_card(page, heading))
        mark(page, *targets, center=False)
    return shot


def s_pengaturan_simpan(page):
    go(page, '/dashboard/settings')
    mark(page, page.get_by_role('button', name='Simpan Pengaturan'))


# (nama file, peran, layar, fungsi)
SHOTS = [
    ('02-01_login', None, 'desktop', s_login),
    ('02-02_login-gagal', None, 'desktop', s_login_gagal),
    ('02-03_tata-letak-panel', 'admin', 'desktop', s_tata_letak),
    ('02-04_menu-akun-keluar', 'admin', 'desktop', s_menu_akun),
    ('02-05_login-hp', None, 'mobile', s_login),

    ('03-01_beranda', None, 'desktop', s_beranda),
    ('03-01m_beranda-hp', None, 'mobile', s_beranda),
    ('03-02_beranda-kategori', None, 'desktop', s_beranda_kategori),
    ('03-03_beranda-unggulan', None, 'desktop', s_beranda_unggulan),
    ('03-04_pencarian', None, 'desktop', s_pencarian),
    ('03-05_katalog', None, 'desktop', s_katalog),
    ('03-05m_katalog-hp', None, 'mobile', s_katalog_hp),
    ('03-06m_katalog-filter-hp', None, 'mobile', s_katalog_filter_hp),
    ('03-07_detail-varian', None, 'desktop', s_detail),
    ('03-07m_detail-hp', None, 'mobile', s_detail_hp),
    ('03-08_detail-tombol-pesan', None, 'desktop', s_detail_tombol),
    ('03-08m_detail-tombol-pesan-hp', None, 'mobile', s_detail_tombol_hp),
    ('03-09_daftar-pesanan', None, 'desktop', s_daftar_pesanan),
    ('03-09m_daftar-pesanan-hp', None, 'mobile', s_daftar_pesanan),
    ('03-10_tentang', None, 'desktop', s_tentang_atas),
    ('03-11_tentang-kontak', None, 'desktop', s_tentang),
    ('03-11m_tentang-kontak-hp', None, 'mobile', s_tentang),
    ('03-12_tombol-tanya-afa', None, 'desktop', s_tombol_tanya_afa),
    ('03-13_tanya-afa', None, 'desktop', s_tanya_afa),
    ('03-13m_tanya-afa-hp', None, 'mobile', s_tanya_afa),
    ('03-14m_navigasi-bawah-hp', None, 'mobile', s_navigasi_bawah),

    ('04-01_dashboard', 'admin', 'desktop', s_dashboard),
    ('04-02_dashboard-ringkasan', 'admin', 'desktop', s_dashboard_bawah),
    ('04-03_produk', 'admin', 'desktop', s_produk),
    ('04-04_produk-filter', 'admin', 'desktop', s_produk_filter),
    ('04-05_produk-stok-cepat', 'admin', 'desktop', s_produk_stok),
    ('04-06_produk-form-informasi', 'admin', 'desktop', s_produk_form_info),
    ('04-07_produk-form-foto', 'admin', 'desktop', s_produk_form_foto),
    ('04-08_produk-form-varian', 'admin', 'desktop', s_produk_form_varian),
    ('04-08b_produk-form-harga', 'admin', 'desktop', s_produk_form_harga),
    ('04-09_produk-form-tampilan', 'admin', 'desktop', s_produk_form_tampilan),
    ('04-10_produk-validasi', 'admin', 'desktop', s_produk_validasi),
    ('04-11_produk-edit-foto', 'admin', 'desktop', s_produk_edit_foto),
    ('04-12_produk-detail', 'admin', 'desktop', s_produk_detail),
    ('04-13_produk-hapus', 'admin', 'desktop', s_produk_hapus),
    ('04-14_kategori', 'admin', 'desktop', s_kategori),
    ('04-15_kategori-form', 'admin', 'desktop', s_kategori_form),
    ('04-16_banner', 'admin', 'desktop', s_banner),
    ('04-17_banner-desktop', 'admin', 'desktop', s_banner_desktop),
    ('04-18_banner-hp', 'admin', 'desktop', s_banner_hp),
    ('04-19_banner-info', 'admin', 'desktop', s_banner_info),
    ('04-20_testimoni', 'admin', 'desktop', s_testimoni),
    ('04-21_testimoni-form', 'admin', 'desktop', s_testimoni_form),
    ('04-22_profil', 'admin', 'desktop', s_profil),
    ('04-23_profil-edit', 'admin', 'desktop', s_profil_edit),
    ('04-24_panel-hp', 'admin', 'mobile', s_dashboard),

    ('05-01_pengguna', 'superadmin', 'desktop', s_pengguna),
    ('05-02_pengguna-status', 'superadmin', 'desktop', s_pengguna_status),
    ('05-03_pengguna-form', 'superadmin', 'desktop', s_pengguna_form),
    ('05-04_pengguna-owner', 'owner', 'desktop', s_pengguna_owner),
    ('05-05_chatbot-riwayat', 'owner', 'desktop', s_chatbot),
    ('05-06_pengaturan-umum', 'owner', 'desktop', settings_tab('Umum', 'Identitas toko')),
    ('05-07_pengaturan-whatsapp', 'owner', 'desktop', settings_tab('WhatsApp & Kontak')),
    ('05-08_pengaturan-beranda', 'owner', 'desktop', settings_tab('Beranda & Tentang')),
    ('05-09_pengaturan-seo', 'owner', 'desktop', settings_tab('Sosial & SEO')),
    ('05-10_pengaturan-chatbot', 'owner', 'desktop', settings_tab('Chatbot AI')),
    ('05-11_pengaturan-simpan', 'owner', 'desktop', s_pengaturan_simpan),

]


def main():
    wanted = sys.argv[1:]
    OUT.mkdir(parents=True, exist_ok=True)
    failed = []
    with sync_playwright() as p:
        browser = p.chromium.launch()
        states = {}
        for name, role, screen, fn in SHOTS:
            if wanted and not any(name.startswith(w) for w in wanted):
                continue
            opts = {'viewport': DESKTOP, 'device_scale_factor': 1, 'locale': 'id-ID', 'reduced_motion': 'reduce'}
            if screen == 'mobile':
                opts.update(viewport=MOBILE, device_scale_factor=2, is_mobile=True, has_touch=True)
            key = (role, screen)
            if role and key not in states:
                ctx = browser.new_context(**opts)
                pg = ctx.new_page()
                login(pg, role)
                states[key] = ctx.storage_state()
                ctx.close()
            ctx = browser.new_context(**opts, storage_state=states.get(key))
            page = ctx.new_page()
            state['floats'] = name in SHOW_FLOATS
            try:
                fn(page)
                page.screenshot(path=str(OUT / f'{name}.png'))
                print('ok  ', name)
            except Exception as e:  # lanjutkan gambar lain, laporkan di akhir
                failed.append(name)
                print('GAGAL', name, str(e).splitlines()[0])
            ctx.close()
        browser.close()
    if failed:
        print('Gagal:', ', '.join(failed))
        sys.exit(1)


if __name__ == '__main__':
    main()
