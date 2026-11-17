@extends('layouts.admin')

@section('title', 'Daftar Paket')
@section('breadcrumb', 'Daftar & Riwayat Status Paket')

@section('content')

    {{-- Header Halaman --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-[22px] font-bold text-[#0A3656]">
                Daftar & Riwayat Status Paket
            </h1>

            <p class="text-[13px] text-slate-500 mt-1">
                Kelola data paket dan pantau riwayat status setiap pengiriman.
            </p>
        </div>

        <a href="{{ route('paket.create') }}"
            class="inline-flex items-center justify-center px-4 py-2.5 bg-[#1D70A2] hover:bg-[#185d87] text-white text-sm font-semibold rounded-lg transition">
            + Tambah Paket
        </a>
    </div>

    {{-- Filter dan Pencarian --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">

        <form action="{{ route('paket.index') }}" method="GET"
            class="flex flex-col sm:flex-row gap-3 w-full">

            <select name="status"
                id="filter-status"
                class="w-full sm:w-48 px-3 py-2.5 border border-slate-200 rounded-lg text-sm bg-white focus:ring-2 focus:ring-[#1D70A2] focus:border-[#1D70A2]">

                <option value="ALL">Semua Status</option>
                <option value="Diterima" @selected(request('status') === 'Diterima')>Diterima</option>
                <option value="Transit" @selected(request('status') === 'Transit')>Transit</option>
                <option value="Dikirim" @selected(request('status') === 'Dikirim')>Dikirim</option>
                <option value="Terkirim" @selected(request('status') === 'Terkirim')>Terkirim</option>
                <option value="Gagal Kirim" @selected(request('status') === 'Gagal Kirim')>Gagal Kirim</option>
                <option value="Retur" @selected(request('status') === 'Retur')>Retur</option>
            </select>

            <input type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Cari No. Resi / Penerima..."
                class="w-full sm:w-72 px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-[#1D70A2] focus:border-[#1D70A2]">

            <button type="submit"
                class="px-5 py-2.5 bg-[#0A3656] hover:bg-[#082b45] text-white text-sm font-semibold rounded-lg transition">
                Cari
            </button>

            <a href="{{ route('paket.index') }}"
                class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-lg text-center transition">
                Reset
            </a>

        </form>
    </div>


    {{-- Tabel Daftar Paket --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">

        {{-- Kolom Kiri: Daftar Paket --}}
        <div class="xl:col-span-8 bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="text-base font-bold text-[#0A3656]">
                    Data Paket Ekspedisi
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Daftar seluruh paket yang tercatat dalam sistem.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Tanggal Masuk</th>
                            <th class="px-5 py-3 font-semibold">No. Resi</th>
                            <th class="px-5 py-3 font-semibold">Penerima & Kota</th>
                            <th class="px-5 py-3 font-semibold">Berat Tagih</th>
                            <th class="px-5 py-3 font-semibold">Ongkir</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse ($paket as $item)
                            <tr
                                onclick="window.location.href='{{ route('paket.index', array_merge(request()->query(), ['detail' => $item->id])) }}'"
                                class="cursor-pointer transition-colors duration-150
                                {{ $detailPaket?->id === $item->id
                                    ? 'bg-sky-50 hover:bg-sky-100'
                                    : 'hover:bg-slate-50' }}"
                            >

                                {{-- Tanggal Masuk --}}
                                <td class="px-5 py-4 text-slate-600 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d M Y') }}
                                </td>

                                {{-- Nomor Resi --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="font-semibold text-[#1D70A2]">
                                        {{ $item->no_resi }}
                                    </span>
                                </td>

                                {{-- Penerima dan Kota --}}
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-700">
                                        {{ $item->penerima_nama }}
                                    </p>
                                    <p class="text-xs text-slate-500 mt-1">
                                        {{ $item->kota_tujuan }}
                                    </p>
                                </td>

                                {{-- Berat Tagih --}}
                                <td class="px-5 py-4 text-slate-600 whitespace-nowrap">
                                    {{ $item->berat_tagih }} kg
                                </td>

                                {{-- Ongkir --}}
                                <td class="px-5 py-4 font-medium text-slate-700 whitespace-nowrap">
                                    Rp{{ number_format($item->ongkir, 0, ',', '.') }}
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @php
                                        $warnaStatus = match ($item->status) {
                                            'Diterima' => 'bg-blue-50 text-blue-700',
                                            'Transit' => 'bg-amber-50 text-amber-700',
                                            'Dikirim' => 'bg-indigo-50 text-indigo-700',
                                            'Terkirim' => 'bg-green-50 text-green-700',
                                            'Gagal Kirim' => 'bg-red-50 text-red-700',
                                            'Retur' => 'bg-slate-100 text-slate-700',
                                            default => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp

                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $warnaStatus }}">
                                        {{ $item->status }}
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <p class="font-semibold text-slate-600">
                                        Belum ada data paket.
                                    </p>
                                    <p class="text-xs text-slate-400 mt-1">
                                        Paket yang berhasil disimpan akan muncul di sini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($paket->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $paket->links() }}
                </div>
            @endif
        </div>

        {{-- Kolom Kanan: Detail Riwayat Resi --}}
        <div class="xl:col-span-4 bg-white border border-slate-200 rounded-xl shadow-sm p-5">

            {{-- Header Detail Resi --}}
            <div class="pb-4 mb-5 border-b border-slate-100">

                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                    Detail Riwayat Resi
                </span>

                @if ($detailPaket)
                    <div class="flex flex-wrap items-center justify-between gap-2 mt-2">
                        <h2 class="text-base font-bold text-[#0A3656]">
                            {{ $detailPaket->no_resi }}
                        </h2>

                        <div class="flex items-center gap-2">

                            <button
                                type="button"
                                onclick="document.getElementById('modal-struk').classList.remove('hidden')"
                                class="bg-sky-50 hover:bg-[#1D70A2] text-[#1D70A2] hover:text-white border border-sky-200 px-2.5 py-1 rounded-md text-xs font-semibold transition">
                                Lihat Struk
                            </button>

                            <span class="text-xs bg-slate-100 text-slate-700 px-2.5 py-1 rounded font-semibold">
                                Zona {{ $detailPaket->zonaTarif?->kode_zona ?? '-' }}
                            </span>
                        </div>

                    </div>

                @else
                    <h2 class="text-base font-bold text-slate-400 mt-1">
                        Belum ada paket
                    </h2>
                @endif

        {{-- Modal Struk --}}
        @if ($detailPaket)
            <div id="modal-struk"
                class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">

                <div id="area-struk" class="bg-white w-full max-w-md rounded-xl shadow-xl overflow-hidden">

                    {{-- Header Struk --}}
                    <div class="bg-[#0A3656] text-white p-5">
                        <h2 class="text-lg font-bold">
                            PRIANGAN KILAT EXPRESS
                        </h2>

                        <p class="text-xs text-blue-100 mt-1">
                            Bukti Penerimaan Paket
                        </p>
                    </div>

                    {{-- Isi Struk --}}
                    <div class="p-5 space-y-4">

                        <div class="text-center pb-4 border-b border-dashed border-slate-300">
                            <p class="text-xs text-slate-500">Nomor Resi</p>
                            <h3 class="text-xl font-bold text-[#1D70A2] mt-1">
                                {{ $detailPaket->no_resi }}
                            </h3>
                        </div>

                        <div class="space-y-3 text-sm">

                            <div class="flex justify-between gap-4">
                                <span class="text-slate-500">Tanggal Masuk</span>
                                <span class="font-medium text-right">
                                    {{ $detailPaket->tanggal_masuk }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-slate-500">Penerima</span>
                                <span class="font-medium text-right">
                                    {{ $detailPaket->penerima_nama }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-slate-500">Kota Tujuan</span>
                                <span class="font-medium text-right">
                                    {{ $detailPaket->kota_tujuan }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-slate-500">Berat Tagih</span>
                                <span class="font-medium">
                                    {{ $detailPaket->berat_tagih }} kg
                                </span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-slate-500">Layanan</span>
                                <span class="font-medium">
                                    {{ $detailPaket->layanan }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-slate-500">Ongkir</span>
                                <span class="font-medium">
                                    Rp{{ number_format($detailPaket->ongkir, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between gap-4">
                                <span class="text-slate-500">Biaya COD</span>
                                <span class="font-medium">
                                    Rp{{ number_format($detailPaket->biaya_cod, 0, ',', '.') }}
                                </span>
                            </div>

                        </div>

                        <div class="border-t border-dashed border-slate-300 pt-4">
                            <p class="text-xs text-slate-500 text-center">
                                Terima kasih telah menggunakan layanan kami.
                            </p>
                        </div>

                    </div>

                    {{-- Footer Modal --}}
                    <div class="no-print flex justify-end gap-2 p-4 bg-slate-50 border-t border-slate-100">

                        <button
                            type="button"
                            onclick="document.getElementById('modal-struk').classList.add('hidden')"
                            class="px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-100">
                            Tutup
                        </button>

                        <button
                            type="button"
                            onclick="window.print()"
                            class="px-4 py-2 text-sm font-semibold text-white bg-[#1D70A2] rounded-lg hover:bg-[#185d87]">
                            Cetak
                        </button>

                    </div>

                </div>
            </div>
        @endif

            </div>

            {{-- Timeline Riwayat Paket --}}
            @if ($detailPaket && $detailPaket->riwayat->count())

                <div class="relative ml-2 border-l-2 border-[#1D70A2] space-y-6">

                    @foreach ($detailPaket->riwayat as $riwayat)

                        <div class="relative pl-5">

                            {{-- Titik Timeline --}}
                            <span class="absolute -left-[7px] top-1 w-3 h-3 rounded-full bg-[#1D70A2] border-2 border-white">
                            </span>

                            {{-- Informasi Riwayat --}}
                            <div>
                                <p class="text-sm font-bold text-[#0A3656]">
                                    {{ $riwayat->status }}
                                </p>

                                <p class="text-xs text-slate-500 mt-1">
                                    {{ $riwayat->created_at?->format('d M Y, H:i') ?? '-' }}
                                </p>

                                <p class="text-xs text-slate-600 mt-2">
                                    {{ $riwayat->catatan ?? 'Tidak ada catatan.' }}
                                </p>
                            </div>
                        </div>

                    @endforeach

                </div>

            @elseif ($detailPaket)

                <p class="text-sm text-slate-500">
                    Belum ada riwayat untuk paket ini.
                </p>

            @else

                <p class="text-sm text-slate-500">
                    Belum ada paket untuk ditampilkan.
                </p>

            @endif

        </div>
    </div>

    @push('scripts')
        @vite('resources/js/pages/paket-index.js')
    @endpush

@endsection