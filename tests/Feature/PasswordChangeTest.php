<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_log_in_again_after_changing_password(): void
    {
        $user = User::factory()->create(['email' => 'owner@example.test', 'is_active' => true]);

        $this->post('/login', ['email' => 'owner@example.test', 'password' => 'password'])->assertRedirect('/dashboard');

        $this->put('/dashboard/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => 'password',
            'password' => 'RahasiaBaru123',
            'password_confirmation' => 'RahasiaBaru123',
        ])->assertSessionHasNoErrors();

        $this->post('/logout');
        $this->assertGuest();

        $this->post('/login', ['email' => 'owner@example.test', 'password' => 'password'])->assertSessionHas('error');
        $this->post('/login', ['email' => 'owner@example.test', 'password' => 'RahasiaBaru123'])->assertRedirect('/dashboard');
    }

    public function test_autofilled_current_password_without_new_password_keeps_old_password(): void
    {
        $user = User::factory()->create(['is_active' => true]);

        $this->actingAs($user)->put('/dashboard/profile', [
            'name' => 'Nama Baru',
            'email' => $user->email,
            'current_password' => 'password',
            'password' => '',
            'password_confirmation' => '',
        ])->assertSessionHasNoErrors();

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('password', $user->fresh()->password));
        $this->assertSame('Nama Baru', $user->fresh()->name);
    }

    public function test_saving_profile_without_password_keeps_old_password(): void
    {
        $user = User::factory()->create(['email' => 'admin@example.test', 'is_active' => true]);
        $hash = $user->password;

        $this->actingAs($user)->put('/dashboard/profile', ['name' => 'Baru', 'email' => $user->email])->assertSessionHasNoErrors();

        $this->assertSame($hash, $user->fresh()->password);
    }
}
