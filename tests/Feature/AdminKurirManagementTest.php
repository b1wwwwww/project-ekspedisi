<?php

namespace Tests\Feature;

use App\Models\Kurir;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminKurirManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_courier_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.kurir.store'), [
            'nama' => 'Kurir Bandung',
            'telepon' => '081234567890',
            'email' => 'kurir@example.com',
            'kode_kurir' => 'KR-001',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.kurir.index'));

        $this->assertDatabaseHas('users', [
            'nama' => 'Kurir Bandung',
            'role' => 'kurir',
            'wajib_ganti_password' => true,
        ]);

        $this->assertDatabaseHas('kurir', [
            'kode_kurir' => 'KR-001',
            'aktif' => true,
        ]);
    }

    public function test_admin_cannot_create_courier_with_invalid_phone_number(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('admin.kurir.create'))
            ->post(route('admin.kurir.store'), [
                'nama' => 'Kurir Bandung',
                'telepon' => 'abc123',
                'email' => 'kurir@example.com',
                'kode_kurir' => 'KR-001',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertRedirect(route('admin.kurir.create'))
            ->assertSessionHasErrors([
                'telepon' => 'Nomor telepon tidak valid. Pastikan hanya berisi angka dan minimal 10 digit.',
            ]);

        $this->assertDatabaseMissing('users', ['email' => 'kurir@example.com']);
    }

    public function test_admin_can_toggle_courier_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pengguna = User::factory()->create(['role' => 'kurir']);
        $kurir = Kurir::create([
            'pengguna_id' => $pengguna->id,
            'kode_kurir' => 'KR-002',
            'area_tugas' => '-',
            'aktif' => true,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.kurir.toggle-status', $kurir))
            ->assertRedirect(route('admin.kurir.index'));

        $this->assertFalse($kurir->fresh()->aktif);
    }

    public function test_courier_cannot_open_admin_courier_management(): void
    {
        $pengguna = User::factory()->create(['role' => 'kurir']);

        $this->actingAs($pengguna)
            ->get(route('admin.kurir.index'))
            ->assertForbidden();
    }
}
