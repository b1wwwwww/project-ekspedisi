<?php

namespace Tests\Feature;

use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\RiwayatPaket;
use App\Models\User;
use App\Models\ZonaTarif;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelacakanResiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_track_a_package_and_see_its_history(): void
    {
        $petugas = User::factory()->create(['nama' => 'Admin Operasional']);
        $pengirim = Pelanggan::create([
            'nama' => 'Toko Maju',
            'telepon' => '081234567890',
        ]);
        $zonaTarif = ZonaTarif::create([
            'nama_zona' => 'Zona 1',
            'harga_per_kg' => 10000,
            'cakupan_wilayah' => 'Bandung Raya',
        ]);
        $paket = Paket::create([
            'no_resi' => 'PKE-261109-0001',
            'tanggal_masuk' => now()->toDateString(),
            'pengirim_id' => $pengirim->id,
            'dibuat_oleh' => $petugas->id,
            'zona_tarif_id' => $zonaTarif->id,
            'penerima_nama' => 'Siti Penerima',
            'penerima_telepon' => '081298765432',
            'penerima_alamat' => 'Jalan Rahasia Nomor 1',
            'kota_tujuan' => 'Bandung Raya',
            'berat_kg' => 1,
            'berat_tagih' => 1,
            'tarif_per_kg' => 10000,
            'ongkir' => 10000,
            'layanan' => 'Reguler',
            'status' => 'Transit',
        ]);
        RiwayatPaket::create([
            'paket_id' => $paket->id,
            'pengguna_id' => $petugas->id,
            'status' => 'Transit',
            'catatan' => 'Paket tiba di Hub Bandung Raya.',
        ]);

        $this->get(route('pelacakan-resi.index', ['resi' => 'pke-261109-0001']))
            ->assertOk()
            ->assertSee('PKE-261109-0001')
            ->assertSee('Transit')
            ->assertSee('Paket tiba di Hub Bandung Raya.')
            ->assertSee('Admin Operasional')
            ->assertDontSee('Jalan Rahasia Nomor 1')
            ->assertDontSee('081298765432');
    }

    public function test_guest_sees_a_message_when_tracking_number_is_not_found(): void
    {
        $this->get(route('pelacakan-resi.index', ['resi' => 'PKE-TIDAK-ADA']))
            ->assertOk()
            ->assertSee('tidak ditemukan');
    }
}
