<?php

namespace App\Services\Import;

use App\Models\Kurir;
use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\RiwayatPaket;
use App\Models\ZonaTarif;
use App\Services\PusatWilayahService;
use App\Services\TeleponNormalizer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaketHistorisImporter
{
    public function __construct(private PusatWilayahService $pusatWilayahService) {}

    /**
     * Mengimpor paket historis tanpa membuat data pengirim atau zona yang tidak valid.
     *
     * @return array{total: int, berhasil: int, dilewati: array<int, string>, peringatan: array<int, string>}
     */
    public function import(string $path, int $penggunaId): array
    {
        $total = 0;
        $berhasil = 0;
        $dilewati = [];
        $peringatan = [];

        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new \RuntimeException('File CSV paket historis tidak dapat dibuka.');
        }

        fgetcsv($handle);

        while (($baris = fgetcsv($handle)) !== false) {
            $total++;

            [$noResi, $tanggalTerima, $namaPengirim, $namaPenerima, $teleponPenerima, $kotaTujuan, $beratKg, $layanan, $status, $kodeKurir, $nilaiCod] = array_pad($baris, 11, null);

            $alasan = $this->validasiBaris(
                $noResi,
                $tanggalTerima,
                $namaPengirim,
                $namaPenerima,
                $teleponPenerima,
                $kotaTujuan,
                $layanan,
                $status,
                $kodeKurir
            );

            if ($alasan !== null) {
                $dilewati[] = "Baris {$total} ({$noResi}): {$alasan}";

                continue;
            }

            $pengirim = Pelanggan::query()
                ->whereRaw('LOWER(nama) = ?', [strtolower(trim($namaPengirim))])
                ->first();

            if ($pengirim === null) {
                // Opsi B: riwayat tanpa pengirim yang sah tidak dipaksakan memakai pelanggan atau telepon palsu.
                $dilewati[] = "Baris {$total} ({$noResi}): nama pengirim tidak ditemukan di data pelanggan.";

                continue;
            }

            $kurir = Kurir::query()->where('kode_kurir', trim($kodeKurir))->first();

            if ($kurir === null) {
                $dilewati[] = "Baris {$total} ({$noResi}): kode kurir tidak ditemukan.";

                continue;
            }

            $kodeZona = $this->pusatWilayahService->ambilZona(trim($kotaTujuan));
            $zonaTarif = $kodeZona === null
                ? null
                : ZonaTarif::query()->where('kode_zona', $kodeZona)->first();

            if ($zonaTarif === null) {
                // CSV tidak membawa pilihan zona manual sehingga baris ini harus diperiksa Admin, bukan diberi tarif tebakan.
                $dilewati[] = "Baris {$total} ({$noResi}): zona kota tidak ditemukan.";

                continue;
            }

            if (Paket::query()->where('no_resi', trim($noResi))->exists()) {
                // Resi duplikat tetap satu data: baris terbaru memperbarui data resi yang sudah ada.
                $peringatan[] = "Baris {$total} ({$noResi}): resi sudah ada dan diperbarui.";
            }

            $teleponRapi = TeleponNormalizer::normalize($teleponPenerima);
            $beratTidakTersedia = ! is_numeric($beratKg) || (float) $beratKg <= 0;
            $beratAktual = $beratTidakTersedia ? 0 : (float) $beratKg;
            $beratTagih = $beratTidakTersedia ? 0 : max(1, ceil($beratAktual));
            $tarifPerKg = (float) $zonaTarif->harga_per_kg;
            $ongkir = $beratTidakTersedia ? 0 : $beratTagih * $tarifPerKg;

            if ($layanan === 'Express' && ! $beratTidakTersedia) {
                $ongkir *= 1.5;
            }

            $nilaiCod = is_numeric($nilaiCod) ? (float) $nilaiCod : 0;
            $biayaCod = $nilaiCod > 0 ? max($nilaiCod * 0.02, 2000) : 0;

            DB::transaction(function () use ($noResi, $tanggalTerima, $pengirim, $kurir, $penggunaId, $zonaTarif, $namaPenerima, $teleponRapi, $kotaTujuan, $beratAktual, $beratTagih, $tarifPerKg, $ongkir, $layanan, $nilaiCod, $biayaCod, $status, $beratTidakTersedia): void {
                $paket = Paket::updateOrCreate(
                    ['no_resi' => trim($noResi)],
                    [
                        'tanggal_masuk' => Carbon::parse($tanggalTerima)->toDateString(),
                        'pengirim_id' => $pengirim->id,
                        'kurir_id' => $kurir->id,
                        'dibuat_oleh' => $penggunaId,
                        'zona_tarif_id' => $zonaTarif->id,
                        // CSV historis tidak menyediakan alamat penerima; tanda ini membedakannya dari alamat yang benar-benar diketahui.
                        'penerima_nama' => $this->rapikanNama($namaPenerima),
                        'penerima_telepon' => $teleponRapi,
                        'penerima_alamat' => '-',
                        'kota_tujuan' => trim($kotaTujuan),
                        'berat_kg' => $beratAktual,
                        'berat_tagih' => $beratTagih,
                        // Tarif disalin ke paket supaya riwayat ongkir tidak berubah ketika tarif zona diedit.
                        'tarif_per_kg' => $tarifPerKg,
                        'ongkir' => $ongkir,
                        'layanan' => $layanan,
                        'nilai_cod' => $nilaiCod,
                        'biaya_cod' => $biayaCod,
                        'status' => $status,
                        'jumlah_gagal' => 0,
                        'zona_manual' => null,
                        'perlu_verifikasi' => $beratTidakTersedia,
                    ]
                );

                RiwayatPaket::updateOrCreate(
                    [
                        'paket_id' => $paket->id,
                        'catatan' => 'Diimpor dari paket_historis.csv.',
                    ],
                    [
                        'pengguna_id' => $penggunaId,
                        'status_dari' => null,
                        'status' => $status,
                    ]
                );
            });

            $berhasil++;
        }

        fclose($handle);

        return [
            'total' => $total,
            'berhasil' => $berhasil,
            'dilewati' => $dilewati,
            'peringatan' => $peringatan,
        ];
    }

    private function validasiBaris(
        mixed $noResi,
        mixed $tanggalTerima,
        mixed $namaPengirim,
        mixed $namaPenerima,
        mixed $teleponPenerima,
        mixed $kotaTujuan,
        mixed $layanan,
        mixed $status,
        mixed $kodeKurir,
    ): ?string {
        if (! is_string($noResi) || blank($noResi)) {
            return 'nomor resi tidak tersedia.';
        }

        if (! is_string($tanggalTerima) || ! $this->tanggalValid($tanggalTerima)) {
            return 'tanggal terima tidak valid.';
        }

        if (! is_string($namaPengirim) || blank($namaPengirim) || ! is_string($namaPenerima) || blank($namaPenerima)) {
            return 'nama pengirim atau penerima tidak tersedia.';
        }

        if (TeleponNormalizer::normalize($teleponPenerima) === null) {
            return 'nomor telepon penerima tidak valid.';
        }

        if (! is_string($kotaTujuan) || blank($kotaTujuan) || ! in_array($layanan, ['Reguler', 'Express'], true)) {
            return 'kota tujuan atau layanan tidak valid.';
        }

        if (! in_array($status, ['Diterima', 'Transit', 'Dikirim', 'Terkirim', 'Gagal Kirim', 'Retur'], true)) {
            return 'status tidak valid.';
        }

        if (! is_string($kodeKurir) || blank($kodeKurir)) {
            return 'kode kurir tidak tersedia.';
        }

        return null;
    }

    private function tanggalValid(string $tanggal): bool
    {
        try {
            Carbon::parse($tanggal);

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    private function rapikanNama(string $nama): string
    {
        return ucwords(strtolower(trim($nama)));
    }
}
