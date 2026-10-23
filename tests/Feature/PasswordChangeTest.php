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

    public function test_corrupted_password_hash_shows_login_error_instead_of_500(): void
    {
        $user = User::factory()->create(['email' => 'rusak@example.test', 'is_active' => true]);
        \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->update(['password' => 'bukan-hash']);

        $this->post('/login', ['email' => 'rusak@example.test', 'password' => 'apa saja'])
            ->assertRedirect()
            ->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_hash_generated_with_cost_10_can_log_in(): void
    {
        $user = User::factory()->create(['email' => 'reset@example.test', 'is_active' => true]);
        \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)
            ->update(['password' => password_hash('PasswordBaru123', PASSWORD_BCRYPT, ['cost' => 10])]);

        $this->post('/login', ['email' => 'reset@example.test', 'password' => 'PasswordBaru123'])->assertRedirect('/dashboard');
    }

    public function test_saving_profile_without_password_keeps_old_password(): void
    {
        $user = User::factory()->create(['email' => 'admin@example.test', 'is_active' => true]);
        $hash = $user->password;

        $this->actingAs($user)->put('/dashboard/profile', ['name' => 'Baru', 'email' => $user->email])->assertSessionHasNoErrors();

        $this->assertSame($hash, $user->fresh()->password);
    }
}
