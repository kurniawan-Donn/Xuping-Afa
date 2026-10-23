<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    private User $admin;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        foreach (['superadmin', 'owner', 'admin'] as $role) {
            Role::create(['name' => $role, 'guard_name' => 'web']);
        }

        $this->owner = User::factory()->create(['is_active' => true]);
        $this->owner->assignRole('owner');
        $this->admin = User::factory()->create(['is_active' => true]);
        $this->admin->assignRole('admin');

        $this->category = Category::create(['name' => 'Kalung', 'slug' => 'kalung']);
    }

    private function makeProduct(array $attributes = []): Product
    {
        return Product::create($attributes + [
            'category_id' => $this->category->id,
            'user_id' => $this->owner->id,
            'created_by' => $this->owner->id,
            'name' => 'Kalung Liontin',
            'slug' => 'kalung-liontin',
            'price' => 99000,
            'stock' => 2,
            'is_active' => true,
        ]);
    }

    public function test_all_admin_pages_render_for_owner(): void
    {
        $product = $this->makeProduct();

        $this->actingAs($this->owner);

        foreach ([
            '/dashboard', '/dashboard/products', '/dashboard/products/create',
            "/dashboard/products/{$product->id}", "/dashboard/products/{$product->id}/edit",
            '/dashboard/categories', '/dashboard/banners', '/dashboard/banners/create',
            '/dashboard/testimonials', '/dashboard/testimonials/create',
            '/dashboard/users', '/dashboard/users/create', "/dashboard/users/{$this->admin->id}/edit",
            '/dashboard/settings', '/dashboard/profile', '/dashboard/profile/edit',
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_admin_cannot_open_settings_or_users(): void
    {
        $this->actingAs($this->admin);

        $this->get('/dashboard/products')->assertOk();
        $this->get('/dashboard/settings')->assertForbidden();
        $this->get('/dashboard/users')->assertForbidden();
    }

    public function test_product_is_created_with_variants_stock_and_webp_images(): void
    {
        $this->actingAs($this->owner)->post('/dashboard/products', [
            'name' => 'Cincin Baru',
            'category_id' => $this->category->id,
            'price' => 120000,
            'compare_price' => 150000,
            'stock' => 7,
            'sizes' => '6, 7; 8, 7',
            'colors' => ['Rose Gold', 'Gold'],
            'badge' => 'promo',
            'is_active' => '1',
            'is_featured' => '1',
            'images' => [UploadedFile::fake()->image('a.jpg', 2400, 1600), UploadedFile::fake()->image('b.png', 400, 400)],
        ])->assertRedirect('/dashboard/products')->assertSessionHasNoErrors();

        $product = Product::where('name', 'Cincin Baru')->with('images')->first();

        $this->assertSame(['6', '7', '8'], $product->sizes);
        $this->assertSame(['Gold', 'Rose Gold'], $product->colors);
        $this->assertSame(7, $product->stock);
        $this->assertTrue($product->is_featured);
        $this->assertCount(2, $product->images);
        $this->assertTrue($product->images->first()->is_primary);

        $path = $product->images->first()->image_path;
        $this->assertStringEndsWith('.webp', $path);
        Storage::disk('public')->assertExists($path);
        $this->assertSame(1600, getimagesizefromstring(Storage::disk('public')->get($path))[0]);
    }

    public function test_compare_price_must_exceed_price(): void
    {
        $this->actingAs($this->owner)->post('/dashboard/products', [
            'name' => 'X',
            'category_id' => $this->category->id,
            'price' => 100000,
            'compare_price' => 90000,
            'stock' => 1,
        ])->assertSessionHasErrors('compare_price');
    }

    public function test_stock_can_be_updated_inline(): void
    {
        $product = $this->makeProduct();

        $this->actingAs($this->admin)
            ->patchJson("/dashboard/products/{$product->id}/stock", ['stock' => 0])
            ->assertOk()->assertJson(['stock' => 0]);

        $this->assertSame(0, $product->fresh()->stock);

        $this->actingAs($this->owner)->get('/dashboard')->assertOk()->assertSee('Kalung Liontin');
    }

    public function test_product_filters_by_stock(): void
    {
        $this->makeProduct(['name' => 'Habis Banget', 'slug' => 'habis', 'stock' => 0]);
        $this->makeProduct(['name' => 'Masih Banyak', 'slug' => 'banyak', 'stock' => 50]);

        $this->actingAs($this->owner)->get('/dashboard/products?stock=out', ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()->assertSee('Habis Banget')->assertDontSee('Masih Banyak');
    }

    public function test_banner_and_testimonial_crud(): void
    {
        $this->actingAs($this->owner);

        $this->post('/dashboard/banners', [
            'title' => 'Koleksi Baru',
            'link_url' => 'javascript:alert(1)',
            'image' => UploadedFile::fake()->image('banner.jpg', 1920, 820),
        ])->assertSessionHasErrors('link_url');

        $this->post('/dashboard/banners', [
            'title' => 'Koleksi Baru',
            'link_url' => '/products?category=kalung',
            'is_active' => '1',
            'image' => UploadedFile::fake()->image('banner.jpg', 1920, 820),
        ])->assertRedirect('/dashboard/banners');

        $this->get('/')->assertSee('Koleksi Baru');

        $this->post('/dashboard/testimonials', ['name' => 'Sari', 'content' => 'Mantap', 'rating' => 4, 'is_active' => '1'])
            ->assertRedirect('/dashboard/testimonials');
        $this->assertSame(4, Testimonial::first()->rating);
    }

    public function test_settings_update_and_whatsapp_number_normalised(): void
    {
        $this->actingAs($this->owner)->post('/dashboard/settings', [
            'whatsapp_number' => '0812 3456 789',
            'whatsapp_greeting' => 'Halo kak, mau pesan:',
            'site_name' => 'Afa Xuping',
            'logo' => UploadedFile::fake()->image('logo.png', 300, 100),
        ])->assertRedirect();

        $this->assertSame('628123456789', Setting::whatsappNumber());
        $this->assertStringEndsWith('.webp', Setting::getValue('logo'));
        $this->get('/')->assertSee('Halo kak, mau pesan:');
    }

    public function test_user_with_products_cannot_be_deleted(): void
    {
        $this->makeProduct(['user_id' => $this->admin->id, 'created_by' => $this->admin->id]);

        $this->actingAs($this->owner)->delete("/dashboard/users/{$this->admin->id}")->assertSessionHas('error');

        $this->assertNotNull($this->admin->fresh());
        $this->assertSame(1, Product::count());

        $empty = User::factory()->create();
        $empty->assignRole('admin');
        $this->actingAs($this->owner)->delete("/dashboard/users/{$empty->id}")->assertSessionHas('success');
        $this->assertNull($empty->fresh());
    }

    public function test_inactive_user_cannot_log_in(): void
    {
        $user = User::factory()->create(['email' => 'off@example.test', 'is_active' => false]);

        $this->post('/login', ['email' => 'off@example.test', 'password' => 'password'])->assertSessionHas('error');
        $this->assertGuest();

        $user->update(['is_active' => true]);
        $this->post('/login', ['email' => 'off@example.test', 'password' => 'password'])->assertRedirect('/dashboard');
    }
}
