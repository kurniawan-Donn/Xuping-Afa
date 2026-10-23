<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $category = Category::create(['name' => 'Cincin', 'slug' => 'cincin']);

        $this->product = Product::create([
            'category_id' => $category->id,
            'user_id' => $user->id,
            'created_by' => $user->id,
            'name' => 'Cincin Permata Bunga',
            'slug' => 'cincin-permata-bunga',
            'price' => 150000,
            'compare_price' => 200000,
            'stock' => 3,
            'sizes' => ['6', '7', '8'],
            'colors' => ['Gold', 'Rose Gold'],
            'badge' => 'new',
            'is_active' => true,
        ]);

        Setting::create(['key' => 'whatsapp_number', 'value' => '0812-3456-789']);
        Testimonial::create(['name' => 'Rina', 'content' => 'Cantik sekali!', 'rating' => 5]);
    }

    public function test_public_pages_render(): void
    {
        $this->get('/')->assertOk()->assertSee('Cincin Permata Bunga')->assertSee('Rina');
        $this->get('/about')->assertOk();
        $this->get('/products')->assertOk()->assertSee('Cincin Permata Bunga');
        $this->get('/products?category=cincin&sort=price_asc&available=1&min_price=100000')->assertOk()->assertSee('Cincin Permata Bunga');
        $this->get('/products?search=kalung')->assertOk()->assertSee('Produk tidak ditemukan');
    }

    public function test_catalog_returns_partial_for_ajax(): void
    {
        $this->get('/products', ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertDontSee('<html', false)
            ->assertSee('Cincin Permata Bunga');
    }

    public function test_product_page_shows_variants_discount_and_seo(): void
    {
        $this->get('/products/cincin-permata-bunga')
            ->assertOk()
            ->assertSee('Rose Gold')
            ->assertSee('Hemat 25%')
            ->assertSee('Tersedia')
            ->assertSee('og:image', false)
            ->assertSee('"@type":"Product"', false)
            ->assertSee('"wa":"628123456789"', false);
    }

    public function test_out_of_stock_product_shows_habis(): void
    {
        $this->product->update(['stock' => 0]);

        $this->get('/products/cincin-permata-bunga')->assertOk()->assertSee('Stok sedang habis')->assertSee('Tanya Ketersediaan');
    }

    public function test_inactive_product_is_hidden(): void
    {
        $this->product->update(['is_active' => false]);

        $this->get('/products/cincin-permata-bunga')->assertNotFound();
        $this->get('/products')->assertDontSee('Cincin Permata Bunga');
    }

    public function test_whatsapp_click_is_counted(): void
    {
        $this->postJson('/wa-click', ['ids' => [$this->product->id, $this->product->id, 99999]])->assertNoContent();

        $this->assertSame(1, $this->product->fresh()->wa_clicks);
    }

    public function test_sitemap_lists_products(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('/products/cincin-permata-bunga', false);
    }

    public function test_active_banner_replaces_hero(): void
    {
        Banner::create([
            'title' => 'Promo Akhir Tahun',
            'image_path' => 'banners/x.webp',
            'is_active' => true,
            'user_id' => $this->product->user_id,
            'created_by' => $this->product->user_id,
        ]);

        $this->get('/')->assertOk()->assertSee('Promo Akhir Tahun');
    }
}
