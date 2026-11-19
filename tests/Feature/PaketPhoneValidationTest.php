<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaketPhoneValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_store_package_with_invalid_phone_numbers(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('paket.create'))
            ->post(route('paket.store'), [
                'telepon_pengirim' => 'abc123',
                'penerima_telepon' => '0812',
            ])
            ->assertRedirect(route('paket.create'))
            ->assertSessionHasErrors([
                'telepon_pengirim' => 'Nomor telepon tidak valid. Pastikan hanya berisi angka dan minimal 10 digit.',
                'penerima_telepon' => 'Nomor telepon tidak valid. Pastikan hanya berisi angka dan minimal 10 digit.',
            ]);
    }
}
