<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kurir;
use App\Models\Paket;
use App\Models\User;
use App\Rules\NomorTeleponValid;
use App\Services\TeleponNormalizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KurirController extends Controller
{
    // Menampilkan daftar akun Kurir beserta pencarian dan status aktif.
    public function index(Request $request): View
    {
        $kurir = Kurir::with(['pengguna', 'paket'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = $request->string('q')->toString();

                $query->where('kode_kurir', 'like', "%{$keyword}%")
                    ->orWhereHas('pengguna', function ($penggunaQuery) use ($keyword) {
                        $penggunaQuery->where('nama', 'like', "%{$keyword}%")
                            ->orWhere('telepon', 'like', "%{$keyword}%");
                    });
            })
            ->withCount('paket')
            ->orderBy('kode_kurir')
            ->paginate(10)
            ->withQueryString();

        return view('admin.kurir.index', compact('kurir'));
    }

    // Menampilkan form pembuatan akun Kurir baru.
    public function create(): View
    {
        return view('admin.kurir.create');
    }

    // Membuat satu akun pengguna dan satu profil Kurir dalam satu transaksi.
    public function store(Request $request): RedirectResponse
    {
        $this->normalizeTelepon($request);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'telepon' => ['required', 'string', 'max:20', 'unique:users,telepon', new NomorTeleponValid],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'kode_kurir' => ['required', 'string', 'max:50', 'unique:kurir,kode_kurir'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($validated) {
            $pengguna = User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'] ?: null,
                'telepon' => $validated['telepon'],
                'password' => Hash::make($validated['password']),
                'role' => 'kurir',
                'wajib_ganti_password' => true,
            ]);

            Kurir::create([
                'pengguna_id' => $pengguna->id,
                'kode_kurir' => $validated['kode_kurir'],
                'area_tugas' => '-',
                'aktif' => true,
            ]);
        });

        return redirect()
            ->route('admin.kurir.index')
            ->with('success', 'Akun Kurir berhasil ditambahkan.');
    }

    // Menampilkan form pengeditan profil Kurir.
    public function edit(Kurir $kurir): View
    {
        $kurir->load('pengguna');

        return view('admin.kurir.edit', compact('kurir'));
    }

    // Memperbarui profil Kurir dan password hanya jika password baru diisi.
    public function update(Request $request, Kurir $kurir): RedirectResponse
    {
        $pengguna = $kurir->pengguna;

        $this->normalizeTelepon($request);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'telepon' => ['required', 'string', 'max:20', Rule::unique('users', 'telepon')->ignore($pengguna->id), new NomorTeleponValid],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($pengguna->id)],
            'kode_kurir' => ['required', 'string', 'max:50', Rule::unique('kurir', 'kode_kurir')->ignore($kurir->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($validated, $pengguna, $kurir) {
            $pengguna->nama = $validated['nama'];
            $pengguna->telepon = $validated['telepon'];
            $pengguna->email = $validated['email'] ?: null;

            if (! empty($validated['password'])) {
                $pengguna->password = Hash::make($validated['password']);
                $pengguna->wajib_ganti_password = true;
            }

            $pengguna->save();

            $kurir->update([
                'kode_kurir' => $validated['kode_kurir'],
            ]);
        });

        return redirect()
            ->route('admin.kurir.index')
            ->with('success', 'Data Kurir berhasil diperbarui.');
    }

    private function normalizeTelepon(Request $request): void
    {
        $telepon = TeleponNormalizer::normalize($request->input('telepon'));

        // Input invalid dibiarkan agar NomorTeleponValid menampilkan pesan yang spesifik.
        if ($telepon !== null) {
            $request->merge(['telepon' => $telepon]);
        }
    }

    // Mengaktifkan atau menonaktifkan akun Kurir tanpa menghapus data.
    public function toggleStatus(Kurir $kurir): RedirectResponse
    {
        // Hanya dicek saat MENONAKTIFKAN; mengaktifkan kembali tidak perlu validasi ini
        if ($kurir->aktif){
            $paketDikirim = Paket::where('kurir_id', $kurir->id)
            ->where('status', 'Dikirim')
            ->count();

        if ($paketDikirim > 0){
            return back()->withErrors([
                'kurir' => "Kurir {$kurir->kode_kurir} masih membawa {$paketDikirim} paket berstatus Dikirim dan tidak dapat di nonaktifkan.",
            ]);
        }
    }

        $kurir->update(['aktif' => ! $kurir->aktif]);

        $status = $kurir->aktif ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.kurir.index')
            ->with('success', "Akun Kurir {$kurir->kode_kurir} berhasil {$status}.");
    }
}
