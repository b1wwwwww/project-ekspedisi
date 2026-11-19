@extends(auth()->user()->role === 'manajer' ? 'layouts.manajer' : 'layouts.admin')

@section('title', 'Manajemen Tarif')
@section('breadcrumb', 'Manajemen Tarif')

@section('content')

    <div class="space-y-6">

        {{-- Header Halaman --}}
        <div>
            <h1 class="text-2xl font-bold text-[#0A3656]">
                Manajemen Tarif
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Lihat dan kelola tarif pengiriman berdasarkan zona wilayah.
            </p>
        </div>

        {{-- Notifikasi Berhasil --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Notifikasi Validasi --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tabel Tarif --}}
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">

            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="text-base font-bold text-[#0A3656]">
                    Daftar Tarif Pengiriman
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Tarif dasar pengiriman untuk setiap zona wilayah per kilogram.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">

                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Kode Zona</th>
                            <th class="px-5 py-3 font-semibold">Nama Zona</th>
                            <th class="px-5 py-3 font-semibold">Cakupan Wilayah</th>
                            <th class="px-5 py-3 font-semibold">Tarif per Kg</th>

                            @if (auth()->user()->role === 'manajer')
                                <th class="px-5 py-3 font-semibold text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse ($zonaTarif as $zona)
                            <tr class="hover:bg-slate-50">

                                <td class="px-5 py-4 font-medium text-slate-600">
                                    Zona {{ $zona->kode_zona }}
                                </td>

                                <td class="px-5 py-4 font-semibold text-[#0A3656]">
                                    {{ $zona->nama_zona }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $zona->cakupan_wilayah }}
                                </td>

                                <td class="px-5 py-4 font-semibold text-slate-700">
                                    Rp{{ number_format($zona->harga_per_kg, 0, ',', '.') }}
                                </td>

                                {{-- Form Edit Khusus Manajer --}}
                                @if (auth()->user()->role === 'manajer')
                                    <td class="px-5 py-4">
                                        <form action="{{ route('manajer.tarif.update', $zona) }}"
                                            method="POST"
                                            class="flex items-center justify-center gap-2">

                                            @csrf
                                            @method('PUT')

                                            <input
                                                type="number"
                                                name="harga_per_kg"
                                                value="{{ old('harga_per_kg', (int) $zona->harga_per_kg) }}"
                                                min="1"
                                                step="1"
                                                required
                                                class="w-32 px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-[#1D70A2] focus:border-[#1D70A2]"
                                            >

                                            <button
                                                type="submit"
                                                class="px-4 py-2 bg-[#0A3656] hover:bg-[#082b45] text-white text-xs font-semibold rounded-lg transition">
                                                Simpan
                                            </button>

                                        </form>
                                    </td>
                                @endif

                            </tr>

                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role === 'manajer' ? 5 : 4 }}"
                                    class="px-5 py-10 text-center text-slate-500">
                                    Belum ada data zona tarif.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>

        {{-- Informasi Tarif --}}
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-5">
            <h3 class="font-bold text-[#0A3656] text-sm">
                Informasi
            </h3>
            <ul class="mt-2 space-y-1 text-sm text-slate-600 list-disc list-inside">
                <li>Tarif berlaku untuk transaksi paket yang baru dibuat.</li>
                <li>Perubahan tarif tidak mengubah biaya paket yang sudah tercatat.</li>
                <li>Admin hanya dapat melihat tarif, sedangkan Manajer dapat mengubahnya.</li>
            </ul>
        </div>

    </div>

@endsection