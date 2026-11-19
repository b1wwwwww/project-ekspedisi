<?php

namespace Tests\Feature;

use App\Models\Kurir;
use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\User;
use App\Models\ZonaTarif;
use App\Services\Import\KurirImporter;
use App\Services\Import\PaketHistorisImporter;
use App\Services\PusatWilayahService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_kurir_importer_creates_courier_accounts_with_normalized_phone_numbers(): void
    {
        $path = $this->buatCsv([
            'kode,nama,telepon',
            'KUR-01,HENDRA UTAMI,+628123456789',
        ]);

        try {
            $hasil = app(KurirImporter::class)->import($path);

            $this->assertSame(1, $hasil['berhasil']);
            $this->assertDatabaseHas('users', [
                'nama' => 'Hendra Utami',
                'telepon' => '08123456789',
                'role' => 'kurir',
                'wajib_ganti_password' => true,
            ]);
            $this->assertDatabaseHas('kurir', ['kode_kurir' => 'KUR-01']);
        } finally {
            unlink($path);
        }
    }

    public function test_paket_historis_importer_skips_unknown_sender_flags_missing_weight_and_merges_duplicate_resi(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pelanggan = Pelanggan::create([
            'nama' => 'Budi Santoso',
            'telepon' => '08123456789',
            'alamat' => 'Bandung',
        ]);
        $kurir = Kurir::create([
            'pengguna_id' => User::factory()->create(['role' => 'kurir'])->id,
            'kode_kurir' => 'KUR-01',
            'area_tugas' => '-',
            'aktif' => true,
        ]);
        ZonaTarif::create([
            'kode_zona' => 1,
            'nama_zona' => 'Bandung Raya',
            'harga_per_kg' => 8000,
            'cakupan_wilayah' => 'Bandung',
        ]);

        $this->mock(PusatWilayahService::class)
            ->shouldReceive('ambilZona')
            ->andReturn(1);

        $path = $this->buatCsv([
            'no_resi,tanggal_terima,pengirim,penerima,telepon_penerima,kota_tujuan,berat_kg,layanan,status,kode_kurir,nilai_cod',
            'PKE-261101-0001,2026-11-01,budi santoso,Nur Hidayat,+628123456780,Bandung,2,Reguler,Dikirim,KUR-01,100000',
            'PKE-261101-0002,2026-11-01,Pengirim Tidak Ada,Nur Hidayat,+628123456780,Bandung,2,Reguler,Dikirim,KUR-01,0',
            'PKE-261101-0003,2026-11-01,BUDI SANTOSO,Nur Hidayat,+628123456780,Bandung,,Express,Terkirim,KUR-01,0',
            'PKE-261101-0001,2026-11-01,BUDI SANTOSO,Nur Hidayat,+628123456780,Bandung,2,Reguler,Dikirim,KUR-01,100000',
        ]);

        try {
            $hasil = app(PaketHistorisImporter::class)->import($path, $admin->id);

            $this->assertSame(4, $hasil['total']);
            $this->assertSame(3, $hasil['berhasil']);
            $this->assertSame(['Baris 2 (PKE-261101-0002): nama pengirim tidak ditemukan di data pelanggan.'], $hasil['dilewati']);
            $this->assertSame(['Baris 4 (PKE-261101-0001): resi sudah ada dan diperbarui.'], $hasil['peringatan']);
            $this->assertSame(2, Paket::query()->count());

            $this->assertDatabaseHas('paket', [
                'no_resi' => 'PKE-261101-0001',
                'pengirim_id' => $pelanggan->id,
                'kurir_id' => $kurir->id,
                'penerima_telepon' => '08123456780',
                'ongkir' => 16000,
                'biaya_cod' => 2000,
            ]);
            $this->assertDatabaseHas('paket', [
                'no_resi' => 'PKE-261101-0003',
                'berat_kg' => 0,
                'ongkir' => 0,
                'perlu_verifikasi' => true,
            ]);
        } finally {
            unlink($path);
        }
    }

    private function buatCsv(array $baris): string
    {
        $path = tempnam(sys_get_temp_dir(), 'import-data-');

        file_put_contents($path, implode(PHP_EOL, $baris));

        return $path;
    }
}
