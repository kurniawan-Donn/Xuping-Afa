<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BannerMobileTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->admin = User::factory()->create();
    }

    public function test_banner_with_mobile_image_and_hidden_mobile_text(): void
    {
        $this->actingAs($this->admin)->post('/dashboard/banners', [
            'title' => 'Koleksi Cincin',
            'is_active' => '1',
            'hide_text_mobile' => '1',
            'image' => UploadedFile::fake()->image('desktop.jpg', 2400, 1028),
            'image_mobile' => UploadedFile::fake()->image('hp.jpg', 1600, 2000),
        ])->assertRedirect('/dashboard/banners')->assertSessionHasNoErrors();

        $banner = Banner::first();
        $this->assertTrue($banner->hide_text_mobile);
        $this->assertStringEndsWith('.webp', $banner->image_mobile_path);
        Storage::disk('public')->assertExists($banner->image_mobile_path);
        $this->assertSame(1080, getimagesizefromstring(Storage::disk('public')->get($banner->image_mobile_path))[0]);

        $this->get('/')
            ->assertOk()
            ->assertSee('<source media="(max-width: 639px)" srcset="' . $banner->mobile_image_url . '">', false)
            ->assertSee('max-sm:hidden', false)
            ->assertSee('aria-label="Koleksi Cincin"', false);
    }

    public function test_banner_without_mobile_image_falls_back_to_desktop(): void
    {
        $this->actingAs($this->admin)->post('/dashboard/banners', [
            'title' => 'Promo Kalung',
            'is_active' => '1',
            'image' => UploadedFile::fake()->image('desktop.jpg', 1920, 820),
        ])->assertSessionHasNoErrors();

        $banner = Banner::first();
        $this->assertNull($banner->image_mobile_path);
        $this->assertFalse($banner->hide_text_mobile);

        $this->get('/')->assertOk()->assertSee('Promo Kalung')->assertDontSee('<source media="(max-width: 639px)"', false);
    }

    public function test_mobile_image_can_be_replaced_and_removed(): void
    {
        $this->actingAs($this->admin)->post('/dashboard/banners', [
            'title' => 'Banner',
            'image' => UploadedFile::fake()->image('d.jpg', 1920, 820),
            'image_mobile' => UploadedFile::fake()->image('m.jpg', 1080, 1350),
        ]);
        $banner = Banner::first();
        $first = $banner->image_mobile_path;

        $this->put("/dashboard/banners/{$banner->id}", [
            'title' => 'Banner',
            'image_mobile' => UploadedFile::fake()->image('m2.jpg', 1080, 1350),
        ])->assertSessionHasNoErrors();
        $second = $banner->fresh()->image_mobile_path;
        $this->assertNotSame($first, $second);
        Storage::disk('public')->assertMissing($first);

        $this->put("/dashboard/banners/{$banner->id}", ['title' => 'Banner', 'remove_image_mobile' => '1'])->assertSessionHasNoErrors();
        $this->assertNull($banner->fresh()->image_mobile_path);
        Storage::disk('public')->assertMissing($second);
        Storage::disk('public')->assertExists($banner->fresh()->image_path);
    }

    public function test_editing_without_touching_mobile_image_keeps_it(): void
    {
        $this->actingAs($this->admin)->post('/dashboard/banners', [
            'title' => 'Banner',
            'image' => UploadedFile::fake()->image('d.jpg', 1920, 820),
            'image_mobile' => UploadedFile::fake()->image('m.jpg', 1080, 1350),
        ]);
        $banner = Banner::first();
        $path = $banner->image_mobile_path;

        $this->put("/dashboard/banners/{$banner->id}", ['title' => 'Judul Baru', 'remove_image_mobile' => '0'])->assertSessionHasNoErrors();

        $this->assertSame($path, $banner->fresh()->image_mobile_path);
        $this->assertSame('Judul Baru', $banner->fresh()->title);
    }

    public function test_deleting_banner_removes_both_images(): void
    {
        $this->actingAs($this->admin)->post('/dashboard/banners', [
            'title' => 'Banner',
            'image' => UploadedFile::fake()->image('d.jpg', 1920, 820),
            'image_mobile' => UploadedFile::fake()->image('m.jpg', 1080, 1350),
        ]);
        $banner = Banner::first();

        $this->delete("/dashboard/banners/{$banner->id}")->assertRedirect('/dashboard/banners');

        Storage::disk('public')->assertMissing($banner->image_path);
        Storage::disk('public')->assertMissing($banner->image_mobile_path);
    }

    public function test_banner_form_renders(): void
    {
        $this->actingAs($this->admin)->get('/dashboard/banners/create')->assertOk()->assertSee('Gambar HP')->assertSee('Sembunyikan teks di HP');
    }
}
