<?php

namespace Tests\Feature;

use App\Models\Pelanggan;
use App\Models\User;
use App\Models\ZonaTarif;
use App\Services\Import\PelangganImporter;
use App\Services\PusatWilayahService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PelangganImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_importer_normalizes_valid_numbers_skips_invalid_rows_and_is_idempotent(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'pelanggan-import-');

        file_put_contents($path, implode(PHP_EOL, [
            'nama,telepon,alamat,kota',
            'budi santoso,+628123456789,Jalan Merdeka,Bandung',
            'SITI,081234567890,Jalan Asia,Jakarta',
            'Nomor Salah,0812,Jalan Cempaka,Bogor',
        ]));

        try {
            $importer = app(PelangganImporter::class);

            $hasil = $importer->import($path);
            $hasilImporKedua = $importer->import($path);

            $this->assertSame(3, $hasil['total']);
            $this->assertSame(2, $hasil['berhasil']);
            $this->assertSame(['Baris 3 (Nomor Salah): nomor telepon tidak valid (0812).'], $hasil['dilewati']);
            $this->assertSame(2, $hasilImporKedua['berhasil']);
            $this->assertSame(2, Pelanggan::query()->count());

            $this->assertDatabaseHas('pelanggan', [
                'nama' => 'Budi Santoso',
                'telepon' => '08123456789',
                'alamat' => 'Jalan Merdeka',
            ]);
        } finally {
            unlink($path);
        }
    }

    public function test_admin_can_upload_customer_csv_and_view_the_import_summary(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        ZonaTarif::create([
            'kode_zona' => 1,
            'nama_zona' => 'Bandung Raya',
            'harga_per_kg' => 8000,
            'cakupan_wilayah' => 'Bandung',
        ]);
        $this->mock(PusatWilayahService::class)
            ->shouldReceive('ambilZona')
            ->andReturn(1);

        $filePelanggan = UploadedFile::fake()->createWithContent('pelanggan.csv', implode(PHP_EOL, [
            'nama,telepon,alamat,kota',
            'Budi,+628123456789,Jalan Merdeka,Bandung',
        ]));
        $fileKurir = UploadedFile::fake()->createWithContent('kurir.csv', implode(PHP_EOL, [
            'kode,nama,telepon',
            'KUR-01,Hendra Utami,+628123456780',
        ]));
        $filePaketHistoris = UploadedFile::fake()->createWithContent('paket_historis.csv', implode(PHP_EOL, [
            'no_resi,tanggal_terima,pengirim,penerima,telepon_penerima,kota_tujuan,berat_kg,layanan,status,kode_kurir,nilai_cod',
            'PKE-261101-0001,2026-11-01,Budi,Nur Hidayat,+628123456781,Bandung,2,Reguler,Dikirim,KUR-01,0',
        ]));

        $this->actingAs($admin)
            ->get(route('admin.import.index'))
            ->assertOk()
            ->assertViewIs('admin.import.index');

        $this->actingAs($admin)
            ->post(route('admin.import.store'), [
                'file_pelanggan' => $filePelanggan,
                'file_kurir' => $fileKurir,
                'file_paket_historis' => $filePaketHistoris,
            ])
            ->assertOk()
            ->assertViewIs('admin.import.hasil')
            ->assertViewHas('hasilPelanggan', [
                'total' => 1,
                'berhasil' => 1,
                'dilewati' => [],
            ]);

        $this->assertDatabaseHas('pelanggan', [
            'nama' => 'Budi',
            'telepon' => '08123456789',
        ]);
    }
}
