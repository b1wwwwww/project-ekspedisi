<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Models\ZonaTarif;
use App\Services\PusatWilayahService;
use Illuminate\Support\Facades\DB;
use App\Models\RiwayatPaket;

class PaketController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data paket beserta relasi yang dibutuhkan.
        $query = Paket::with([
            'pengirim',
            'kurir',
            'zonaTarif',
            'riwayat' => function ($query){ // mengambil catatan perubahan status paket.
                $query->orderBy('created_at', 'desc'); // mengurutkan dri yg terbaru
                },     
            ]);

        // Filter berdasarkan status.
        if ($request->filled('status') && $request->status !== 'ALL') {
            $query->where('status', $request->status);
        }

        // Pencarian berdasarkan nomor resi atau nama penerima.
        if ($request->filled('q')) {
            $keyword = $request->q;

            $query->where(function ($query) use ($keyword) {
                $query->where('no_resi', 'like', "%{$keyword}%")
                    ->orWhere('penerima_nama', 'like', "%{$keyword}%");
            });
        }

        // Mengambil data paket dengan pagination.
        $paket = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();
        
        // Menentukan paket yang akan ditampilkan detailnya.
        $detailPaket = $paket->firstWhere('id', $request->query('detail'))
            ?? $paket->first(); 

        return view('paket.index', compact('paket', 'detailPaket'));
    }

    public function create()
    {
        $zonaTarif = ZonaTarif::all();
        return view ('paket.create', compact('zonaTarif')); // menampilkan form tambah paket
    }

    

    public function store(
        // menerima Request agar bisa membaca data dari form tambah paket.
        Request $request,
        PusatWilayahService $pusatWilayahService
    ) {
        $validated = $request->validate([
            'telepon_pengirim' => 'required|string|max:20',
            'nama_pengirim'    => 'required|string|max:255',
            'alamat_pengirim'  => 'nullable|string',

            'penerima_nama'    => 'required|string|max:255',
            'penerima_telepon' => 'required|string|max:20',
            'penerima_alamat'  => 'required|string',
            'kota_tujuan'      => 'required|string|max:255',

            'berat_kg'         => 'required|numeric|gt:0',
            'layanan'          => 'required|in:Reguler,Express',
            'nilai_cod'        => 'required|numeric|min:0',
            'zona_manual'      => 'nullable|exists:zona_tarif,id'
        ]);

        // Menentukan zona tujuan.
        $zonaManualDipakai = false;

        if (!empty($validated['zona_manual'])) {

            // Jika admin memilih zona manual, cari berdasarkan ID.
            $zonaTarif = ZonaTarif::find($validated['zona_manual']);

            $zonaManualDipakai = true;

        } else {

            // Jika tidak memilih manual, gunakan API.
            $kodeZona = $pusatWilayahService->ambilZona(
                $validated['kota_tujuan']
            );

            $zonaTarif = null;

            if ($kodeZona !== null) {
                $zonaTarif = ZonaTarif::where(
                    'kode_zona',
                    $kodeZona
                )->first();
            }

            // Jika API gagal, minta admin memilih zona manual.
            if (!$zonaTarif) {
                return back()
                    ->withErrors([
                        'zona_manual' => 'Zona tidak ditemukan. Silakan pilih zona secara manual.'
                    ])
                    ->withInput();
            }
        }

        // Menentukan informasi zona manual.
        $kodeZonaManual = $zonaManualDipakai
            ? (string) $zonaTarif->kode_zona
            : null;

        $perluVerifikasi = $zonaManualDipakai ? 1 : 0;

        // Hitung berat tagih, tarif per kg, ongkir, dan biaya COD
        $beratTagih = max(1, ceil($validated['berat_kg']));
        $tarifPerKg = $zonaTarif->harga_per_kg;

        // menghitung ongkir (Reguler)
        $ongkir = $beratTagih * $tarifPerKg;

        // jika layanan adalah Express, ongkir dikalikan 1.5
        if ($validated['layanan'] === 'Express') {
            $ongkir = (int) ($ongkir * 1.5);
        }


        // menghitung biaya COD 
        $biayaCod = 0;

        // jika nilai COD lebih dari 0, biaya COD dihitung sebagai 2%
        // dari nilai COD, dengan minimal Rp 2.000.
        if ($validated['nilai_cod'] > 0) {
            $biayaCod = (int) max(
                $validated['nilai_cod'] * 0.02,
                2000
            );
        }


    // Menyimpan pelanggan, paket, dan riwayat dalam satu transaksi.
    $paket = DB::transaction(function () use (
        $validated,
        $zonaTarif,
        $beratTagih,
        $tarifPerKg,
        $ongkir,
        $biayaCod,
        $kodeZonaManual,
        $perluVerifikasi
    ) {

    // 1. Cari pengirim berdasarkan nomor telepon.
    // Jika belum ada, buat pelanggan baru.
    $pengirim = Pelanggan::firstOrCreate(
        ['telepon' => $validated['telepon_pengirim']],
        [
            'nama' => $validated['nama_pengirim'],
            'alamat' => $validated['alamat_pengirim'],
        ]
    );

    // 2. Buat nomor resi otomatis.
    $nomorResi = $this->buatNomorResi();

    // 3. Simpan data paket.
    $paket = Paket::create([
        'no_resi' => $nomorResi,
        'tanggal_masuk' => now()->toDateString(),
        'pengirim_id' => $pengirim->id,
        'kurir_id' => null,
        'dibuat_oleh' => auth()->id(),
        'zona_tarif_id' => $zonaTarif->id,
        'zona_manual' => $kodeZonaManual,
        'perlu_verifikasi' => $perluVerifikasi,

        'penerima_nama' => $validated['penerima_nama'],
        'penerima_telepon' => $validated['penerima_telepon'],
        'penerima_alamat' => $validated['penerima_alamat'],
        'kota_tujuan' => $validated['kota_tujuan'],

        'berat_kg' => $validated['berat_kg'],
        'berat_tagih' => $beratTagih,
        'tarif_per_kg' => $tarifPerKg,
        'ongkir' => $ongkir,

        'layanan' => $validated['layanan'],
        'nilai_cod' => $validated['nilai_cod'],
        'biaya_cod' => $biayaCod,

        'status' => 'Diterima',
        'jumlah_gagal' => 0,
    ]);

    // 4. Catat riwayat pertama paket.
    RiwayatPaket::create([
        'paket_id' => $paket->id,
        'pengguna_id' => auth()->id(),
        'status_dari' => null,
        'status' => 'Diterima',
        'catatan' => 'Paket diterima di loket.',
    ]);

        return $paket;
    });


    // 5. Jika berhasil, kembali ke halaman daftar paket.
    return redirect()
        ->route('paket.index')
        ->with(
            'success',
            'Paket berhasil disimpan. Nomor resi: ' . $paket->no_resi
        );

    }
    private function buatNomorResi(): string
    {
        // Ambil tanggal hari ini dalam format YYYY-MM-DD
        $tanggal = now()->toDateString(); 

        // Jika tanggal belum ada di tabel, buat penghitung baru dengan nilai 0.
        DB::table('urutan_resi')->insertOrIgnore([
            'tanggal' => $tanggal, // jika sudah ada, tidak akan menambahkan data baru
            'nomor_terakhir' => 0,
        ]);

        // Ambil dan kunci penghitung agar tidak digunakan bersamaan.
        $urutan = DB::table('urutan_resi')
            ->where('tanggal', $tanggal)
            ->lockForUpdate()
            ->first();

        // Tambahkan nomor terakhir.
        $nomor = $urutan->nomor_terakhir + 1;

        // Simpan nomor terbaru.
        DB::table('urutan_resi')
            ->where('tanggal', $tanggal)
            ->update([
                'nomor_terakhir' => $nomor,
            ]);

        // Format: PKE-YYMMDD-0001
        // Mengubah Angka 1 menjadi 0001, angka 12 menjadi 012.
        return 'PKE-' . now()->format('ymd') . '-' . str_pad($nomor, 4, '0', STR_PAD_LEFT);
    }


    public function hitungTarif(Request $request, PusatWilayahService $pusatWilayahService)
    {
        $validated = $request->validate([
            'kota_tujuan' => 'required|string|max:255',
            'berat_kg' => 'required|numeric|gt:0',
            'layanan' => 'required|in:Reguler,Express',
            'nilai_cod' => 'nullable|numeric|min:0',
            'zona_manual' => 'nullable|exists:zona_tarif,id',
        ]);

        // 1. Menentukan zona tarif berdasarkan kota tujuan atau zona manual.
        if (!empty($validated['zona_manual'])) {

            // Menggunakan zona pilihan Admin
            $zonaTarif = ZonaTarif::findOrFail($validated['zona_manual']);

        } else {

            //2. Meminta zona secara otomatis melalui API
            $kodeZona = $pusatWilayahService->ambilZona($validated['kota_tujuan']);

            if ($kodeZona === null) {
                return response()->json([
                    'message' => 'Zona tidak ditemukan. Silakan pilih zona secara manual.',
                    'perlu_zona_manual' => true,
                ], 422);
            }

            $zonaTarif = ZonaTarif::where('kode_zona', $kodeZona)->first();

            // Jika kode zona dari API tidak ditemukan, Admin memilih zona secara manual.
            if (!$zonaTarif) {
                return response()->json([
                    'message' => 'Tarif zona belum tersedia. Silakan pilih zona secara manual.',
                    'perlu_zona_manual' => true,
                ], 422);
            }
        }   

        // 3. Menghitung berat tagih
        $beratTagih = max(1, ceil($validated['berat_kg']));

        // 4. Menghitung ongkos kirim
        $tarifPerKg = $zonaTarif->harga_per_kg;
        $ongkir = $beratTagih * $tarifPerKg;

        // Perhitungan express
        if ($validated['layanan'] === 'Express') {
            $ongkir = (int) ($ongkir * 1.5);
        }

        // 5. Menghitung biaya COD
        $nilaiCod = $validated['nilai_cod'] ?? 0;
        $biayaCod = 0;

        if ($nilaiCod > 0) {
            $biayaCod = (int) max($nilaiCod * 0.02, 2000);
        }

        return response()->json([
            'zona' => $zonaTarif->kode_zona,
            'berat_tagih' => $beratTagih,
            'tarif_per_kg' => $tarifPerKg,
            'ongkir' => $ongkir,
            'biaya_cod' => $biayaCod,
        ]);
    }
}
