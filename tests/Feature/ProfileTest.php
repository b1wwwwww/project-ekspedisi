<?php

namespace Tests\Feature;

use App\Models\Kurir;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'nama' => 'Test User',
                'email' => 'test@example.com',
                'telepon' => '081234567890',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->nama);
        $this->assertSame('test@example.com', $user->email);
        $this->assertSame('081234567890', $user->telepon);
    }

    public function test_profile_information_can_be_updated_without_email_and_phone(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'nama' => 'Test User',
                'email' => null,
                'telepon' => null,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->nama);
        $this->assertNull($user->email);
        $this->assertNull($user->telepon);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }

    public function test_courier_password_change_completes_first_login_flow(): void
    {
        $user = User::factory()->create([
            'role' => 'kurir',
            'password' => 'password123',
            'wajib_ganti_password' => true,
        ]);

        Kurir::create([
            'pengguna_id' => $user->id,
            'kode_kurir' => 'KUR-TEST',
            'area_tugas' => '-',
            'aktif' => true,
        ]);

        $profileResponse = $this->actingAs($user)->get('/profile');
        $profileResponse->assertOk();
        $profileResponse->assertViewIs('profile.kurir-edit');

        $passwordResponse = $this->actingAs($user)->put('/password', [
            'current_password' => 'password123',
            'password' => 'password456',
            'password_confirmation' => 'password456',
        ]);

        $passwordResponse->assertRedirect(route('kurir.penugasan.index'));

        $this->assertFalse($user->fresh()->wajib_ganti_password);
    }
}
