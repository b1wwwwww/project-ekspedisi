<?php

namespace Tests\Feature;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelangganAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_customer_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('pelanggan.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('pelanggan.create'))
            ->assertOk();
    }

    public function test_admin_can_view_customer_edit_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $pelanggan = Pelanggan::create([
            'nama' => 'Budi',
            'telepon' => '081234567890',
            'alamat' => 'Bandung',
        ]);

        $this->actingAs($admin)
            ->get(route('pelanggan.edit', $pelanggan))
            ->assertOk();
    }

    public function test_manager_can_only_view_customer_list(): void
    {
        $manajer = User::factory()->create(['role' => 'manajer']);

        $this->actingAs($manajer)
            ->get(route('pelanggan.index'))
            ->assertOk();

        $this->actingAs($manajer)
            ->get(route('pelanggan.create'))
            ->assertForbidden();

        $pelanggan = Pelanggan::create([
            'nama' => 'Budi',
            'telepon' => '081234567890',
            'alamat' => 'Bandung',
        ]);

        $this->actingAs($manajer)
            ->get(route('pelanggan.edit', $pelanggan))
            ->assertForbidden();
    }

    public function test_manager_cannot_create_customer(): void
    {
        $manajer = User::factory()->create(['role' => 'manajer']);

        $this->actingAs($manajer)
            ->post(route('pelanggan.store'), [
                'nama' => 'Andi',
                'telepon' => '081234567891',
                'alamat' => 'Bandung',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('pelanggan', [
            'telepon' => '081234567891',
        ]);
    }

    public function test_admin_store_normalizes_customer_phone_number(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('pelanggan.store'), [
                'nama' => 'Andi',
                'telepon' => '+628123456789',
                'alamat' => 'Bandung',
            ])
            ->assertRedirect(route('pelanggan.index'));

        $this->assertDatabaseHas('pelanggan', [
            'nama' => 'Andi',
            'telepon' => '08123456789',
        ]);
    }

    public function test_manager_cannot_update_customer(): void
    {
        $manajer = User::factory()->create(['role' => 'manajer']);

        $pelanggan = Pelanggan::create([
            'nama' => 'Budi',
            'telepon' => '081234567890',
            'alamat' => 'Bandung',
        ]);

        $this->actingAs($manajer)
            ->put(route('pelanggan.update', $pelanggan), [
                'nama' => 'Budi Baru',
                'telepon' => '081234567890',
                'alamat' => 'Jakarta',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('pelanggan', [
            'id' => $pelanggan->id,
            'nama' => 'Budi',
            'alamat' => 'Bandung',
        ]);
    }

    public function test_guest_cannot_view_customer_list(): void
    {
        $this->get(route('pelanggan.index'))
            ->assertRedirect(route('login'));
    }
}
