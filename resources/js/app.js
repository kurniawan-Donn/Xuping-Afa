import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal;

const AFA = window.AFA || {};

export const rupiah = (n) => 'Rp ' + Math.round(Number(n) || 0).toLocaleString('id-ID');
window.rupiah = rupiah;

const storage = {
    get(key, fallback) {
        try {
            return JSON.parse(localStorage.getItem(key)) ?? fallback;
        } catch {
            return fallback;
        }
    },
    set(key, value) {
        try {
            localStorage.setItem(key, JSON.stringify(value));
        } catch {
            // mode privat / penyimpanan penuh: abaikan, daftar tetap jalan selama halaman terbuka
        }
    },
};

const variantText = (item) =>
    [item.size && `Ukuran: ${item.size}`, item.color && `Warna: ${item.color}`].filter(Boolean).join(' | ');

const openWhatsApp = (text, productIds = []) => {
    if (!AFA.wa) {
        Swal.fire({ icon: 'info', title: 'Nomor WhatsApp belum diatur', text: 'Silakan hubungi kami melalui kontak di halaman Tentang.' });
        return false;
    }

    if (productIds.length && AFA.clickUrl) {
        fetch(AFA.clickUrl, {
            method: 'POST',
            keepalive: true,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': AFA.csrf, Accept: 'application/json' },
            body: JSON.stringify({ ids: productIds }),
        }).catch(() => {});
    }

    window.open(`https://wa.me/${AFA.wa}?text=${encodeURIComponent(text)}`, '_blank', 'noopener');
    return true;
};

/* ---------------------------------------------------------------
 * Toast kecil di pojok layar
 * ------------------------------------------------------------- */
Alpine.store('toast', {
    items: [],
    show(message, type = 'success') {
        const id = Date.now() + Math.random();
        this.items.push({ id, message, type });
        setTimeout(() => (this.items = this.items.filter((t) => t.id !== id)), 2800);
    },
});

/* ---------------------------------------------------------------
 * Daftar Pesanan: keranjang ringan yang dikirim sebagai satu pesan WhatsApp
 * ------------------------------------------------------------- */
Alpine.store('cart', {
    items: storage.get('afa_cart', []),
    customer: storage.get('afa_customer', { name: '', address: '', note: '' }),
    open: false,

    get count() {
        return this.items.reduce((sum, i) => sum + i.qty, 0);
    },
    get total() {
        return this.items.reduce((sum, i) => sum + i.qty * i.price, 0);
    },

    persist() {
        storage.set('afa_cart', this.items);
        storage.set('afa_customer', this.customer);
    },

    add(product, { size = null, color = null, qty = 1 } = {}) {
        const key = [product.id, size, color].join('|');
        const existing = this.items.find((i) => i.key === key);

        if (existing) {
            existing.qty = Math.min(existing.qty + qty, 99);
        } else {
            this.items.push({ key, ...product, size, color, qty });
        }

        this.persist();
        Alpine.store('toast').show('Ditambahkan ke Daftar Pesanan');
    },
    setQty(item, qty) {
        item.qty = Math.max(1, Math.min(99, Number(qty) || 1));
        this.persist();
    },
    remove(item) {
        this.items = this.items.filter((i) => i.key !== item.key);
        this.persist();
    },
    clear() {
        this.items = [];
        this.persist();
    },

    message() {
        const lines = [AFA.greeting || 'Halo, saya ingin memesan:', ''];

        this.items.forEach((item, idx) => {
            lines.push(`${idx + 1}. *${item.name}*`);
            const variant = variantText(item);
            if (variant) lines.push(`   ${variant}`);
            lines.push(`   ${item.qty} x ${rupiah(item.price)} = ${rupiah(item.qty * item.price)}`);
            lines.push(`   ${item.url}`);
            lines.push('');
        });

        lines.push(`*Total: ${rupiah(this.total)}*`);

        const c = this.customer;
        if (c.name || c.address || c.note) {
            lines.push('');
            if (c.name) lines.push(`Nama: ${c.name}`);
            if (c.address) lines.push(`Alamat: ${c.address}`);
            if (c.note) lines.push(`Catatan: ${c.note}`);
        }

        return lines.join('\n');
    },

    send() {
        if (!this.items.length) return;
        this.persist();

        if (openWhatsApp(this.message(), this.items.map((i) => i.id))) {
            Swal.fire({
                icon: 'success',
                title: 'Pesanan dibuka di WhatsApp',
                text: 'Kosongkan Daftar Pesanan sekarang?',
                showCancelButton: true,
                confirmButtonText: 'Ya, kosongkan',
                cancelButtonText: 'Simpan dulu',
                confirmButtonColor: '#1c1917',
            }).then((r) => r.isConfirmed && (this.clear(), (this.open = false)));
        }
    },
});

/* ---------------------------------------------------------------
 * Halaman detail produk: pilih varian, jumlah, lalu tambah / pesan langsung
 * ------------------------------------------------------------- */
Alpine.data('productOrder', (product, sizes = [], colors = []) => ({
    size: sizes.length === 1 ? sizes[0] : null,
    color: colors.length === 1 ? colors[0] : null,
    qty: 1,
    attempted: false,

    get missing() {
        return [sizes.length && !this.size && 'ukuran', colors.length && !this.color && 'warna'].filter(Boolean);
    },
    validate() {
        this.attempted = true;
        if (this.missing.length) {
            Alpine.store('toast').show(`Pilih ${this.missing.join(' & ')} dulu`, 'error');
            return false;
        }
        return true;
    },
    addToCart() {
        if (!this.validate()) return;
        Alpine.store('cart').add(product, { size: this.size, color: this.color, qty: this.qty });
    },
    orderNow() {
        if (!this.validate()) return;
        const item = { ...product, size: this.size, color: this.color, qty: this.qty };
        const lines = [AFA.greeting || 'Halo, saya ingin memesan:', '', `*${item.name}*`];
        const variant = variantText(item);
        if (variant) lines.push(variant);
        lines.push(`${item.qty} x ${rupiah(item.price)} = ${rupiah(item.qty * item.price)}`, item.url);
        openWhatsApp(lines.join('\n'), [item.id]);
    },
    askStock() {
        openWhatsApp(`Halo, apakah *${product.name}* akan tersedia lagi?\n${product.url}`, [product.id]);
    },
}));

/* ---------------------------------------------------------------
 * Galeri foto: geser (scroll-snap), thumbnail, dan lightbox
 * ------------------------------------------------------------- */
Alpine.data('gallery', (count) => ({
    active: 0,
    lightbox: false,
    count,
    go(i) {
        this.active = (i + this.count) % this.count;
        const track = this.$refs.track;
        track?.scrollTo({ left: track.clientWidth * this.active, behavior: 'smooth' });
    },
    onScroll() {
        const track = this.$refs.track;
        this.active = Math.round(track.scrollLeft / track.clientWidth);
    },
}));

/* ---------------------------------------------------------------
 * Filter live: dipakai katalog publik dan tabel dashboard.
 * Isi container diganti dengan HTML partial dari server (request AJAX).
 * ------------------------------------------------------------- */
Alpine.data('liveFilter', (filters = {}, extra = {}) => ({
    ...filters,
    ...extra,
    loading: false,
    controller: null,

    init() {
        Object.keys(filters).forEach((key) => this.$watch(key, () => this.fetchData()));
        this.bindPagination();
        window.addEventListener('popstate', () => this.fetchData(window.location.href, false));
    },

    query() {
        const params = new URLSearchParams();
        Object.keys(filters).forEach((key) => {
            const value = this[key];
            if (value !== '' && value !== null && value !== false && value !== undefined) params.set(key, value === true ? 1 : value);
        });
        return params.toString();
    },

    fetchData(url = null, push = true) {
        const qs = this.query();
        const target = url || window.location.pathname + (qs ? `?${qs}` : '');

        this.controller?.abort();
        this.controller = new AbortController();
        this.loading = true;

        if (push) {
            const u = new URL(target, window.location.origin);
            window.history.replaceState({}, '', u.pathname + u.search);
        }

        fetch(target, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: this.controller.signal })
            .then((res) => {
                if (!res.ok) throw new Error(res.status);
                return res.text();
            })
            .then((html) => {
                this.$refs.results.innerHTML = html;
                this.bindPagination();
                if (url) this.$refs.results.scrollIntoView({ behavior: 'smooth', block: 'start' });
            })
            .catch((e) => {
                if (e.name !== 'AbortError') Alpine.store('toast').show('Gagal memuat data, coba lagi.', 'error');
            })
            .finally(() => (this.loading = false));
    },

    bindPagination() {
        this.$nextTick(() => {
            this.$refs.results.querySelectorAll('nav a[href]').forEach((link) => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.fetchData(link.href);
                });
            });
        });
    },

    reset() {
        Object.keys(filters).forEach((key) => (this[key] = typeof filters[key] === 'boolean' ? false : ''));
    },
}));

/* ---------------------------------------------------------------
 * Dashboard: kelola foto produk (pratinjau upload, hapus, gambar utama)
 * ------------------------------------------------------------- */
Alpine.data('imageManager', (existing = []) => ({
    existing,
    deleted: [],
    primaryId: existing.find((i) => i.is_primary)?.id ?? null,
    previews: [],
    dragging: false,

    get visibleExisting() {
        return this.existing.filter((i) => !this.deleted.includes(i.id));
    },

    pick(fileList) {
        const dt = new DataTransfer();
        this.previews.forEach((p) => dt.items.add(p.file));
        Array.from(fileList)
            .filter((f) => f.type.startsWith('image/'))
            .forEach((file) => {
                dt.items.add(file);
                this.previews.push({ file, url: URL.createObjectURL(file) });
            });
        this.$refs.input.files = dt.files;
    },
    removePreview(index) {
        URL.revokeObjectURL(this.previews[index].url);
        this.previews.splice(index, 1);
        const dt = new DataTransfer();
        this.previews.forEach((p) => dt.items.add(p.file));
        this.$refs.input.files = dt.files;
    },
    toggleDelete(id) {
        this.deleted = this.deleted.includes(id) ? this.deleted.filter((d) => d !== id) : [...this.deleted, id];
        if (this.deleted.includes(this.primaryId)) this.primaryId = this.visibleExisting[0]?.id ?? null;
    },
}));

/* ---------------------------------------------------------------
 * Pratinjau satu gambar (banner, pengaturan, avatar)
 * ------------------------------------------------------------- */
Alpine.data('imagePreview', (url = null) => ({
    url,
    pick(e) {
        const file = e.target.files[0];
        if (file) this.url = URL.createObjectURL(file);
    },
}));

/* ---------------------------------------------------------------
 * Konfirmasi hapus untuk form dengan atribut data-confirm
 * ------------------------------------------------------------- */
document.addEventListener('submit', (e) => {
    const form = e.target;
    if (!form.matches('form[data-confirm]') || form.dataset.confirmed) return;

    e.preventDefault();
    Swal.fire({
        icon: 'warning',
        title: 'Yakin?',
        text: form.dataset.confirm,
        showCancelButton: true,
        confirmButtonText: 'Ya, lanjutkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc2626',
        reverseButtons: true,
    }).then((r) => {
        if (r.isConfirmed) {
            form.dataset.confirmed = '1';
            form.requestSubmit ? form.requestSubmit() : form.submit();
        }
    });
});

Alpine.plugin(collapse);
Alpine.start();
