@extends('layouts.admin')

@section('title', 'Penugasan Kurir')
@section('breadcrumb', 'Penugasan Kurir')

@section('content')
    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Notifikasi error --}}
    @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="mb-1 font-semibold">Penugasan gagal!</p>

            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Judul Halaman --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#0A3656]">
            Penugasan Kurir
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Kelola penugasan paket kepada kurir aktif Priangan Kilat Express.
        </p>
    </div>
    
    {{-- Form Penugasan --}}
    <form action="{{ route('admin.penugasan-kurir.store') }}" method="POST">
        @csrf

    {{-- Konten Utama --}}
    <div class="grid grid-cols-12 gap-6 items-start">

        {{-- Kolom Kiri: Daftar Paket --}}
        <div class="col-span-8">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-[#0A3656]">
                            Paket Siap Ditugaskan
                        </h2>

                        <p class="mt-1 text-xs text-slate-500">
                            Pilih paket berstatus Transit atau Gagal Kirim 1x.
                        </p>
                    </div>

                    {{-- Filter Zona --}}
                    <select
                        id="filter-zona-penugasan"
                        name="zona"
                        onchange="window.location.href='?zona=' + this.value"
                        class="rounded-lg border border-slate-300 px-3 py-2 text-sm">

                        <option value="ALL" {{ request('zona', 'ALL') === 'ALL' ? 'selected' : '' }}>
                            Semua Zona
                        </option>

                        <option value="1" {{ request('zona') === '1' ? 'selected' : '' }}>
                            Zona 1 - Bandung Raya
                        </option>

                        <option value="2" {{ request('zona') === '2' ? 'selected' : '' }}>
                            Zona 2 - Jawa Barat
                        </option>

                        <option value="3" {{ request('zona') === '3' ? 'selected' : '' }}>
                            Zona 3 - Luar Jawa Barat
                        </option>
                    </select>
                </div>

                {{-- Tabel Paket --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                            <tr>
                                <th class="px-3 py-3">
                                    Pilih
                                </th>
                                <th class="px-3 py-3">
                                    No. Resi
                                </th>
                                <th class="px-3 py-3">
                                    Penerima
                                </th>
                                <th class="px-3 py-3">
                                    Kota
                                </th>
                                <th class="px-3 py-3">
                                    Zona
                                </th>
                                <th class="px-3 py-3">
                                    Kurir Sebelumnya
                                </th>
                            </tr>
                        </thead>

                        <tbody id="penugasan-paket-tbody" class="divide-y divide-slate-100">

                        @forelse ($paket as $item)
                            <tr class="hover:bg-slate-50">

                                {{-- Checkbox --}}
                                <td class="px-3 py-4">
                                    <input
                                        type="checkbox"
                                        name="paket_id[]"
                                        value="{{ $item->id }}"
                                        data-kurir-sebelumnya="{{ $item->kurir_id ?? '' }}"
                                        class="checkbox-paket rounded border-slate-300 text-[#0A3656]">
                                </td>

                                {{-- Nomor Resi --}}
                                <td class="px-3 py-4 font-semibold text-[#0A3656]">
                                    {{ $item->no_resi }}
                                </td>

                                {{-- Penerima --}}
                                <td class="px-3 py-4">
                                    {{ $item->penerima_nama }}
                                </td>

                                {{-- Kota --}}
                                <td class="px-3 py-4">
                                    {{ $item->kota_tujuan }}
                                </td>

                                {{-- Zona --}}
                                <td class="px-3 py-4">
                                    <span class="rounded-full bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700">
                                        {{ $item->zonaTarif->nama_zona ?? 'Tidak diketahui' }}
                                    </span>
                                </td>

                                {{-- Kurir Sebelumnya --}}
                                <td class="px-3 py-4">
                                    @if ($item->status === 'Gagal Kirim' && $item->jumlah_gagal == 1)
                                        <div class="font-medium text-slate-700">
                                            {{ $item->kurir->pengguna->nama ?? 'Tidak diketahui' }}
                                        </div>

                                        <div class="text-xs text-slate-400">
                                            {{ $item->kurir->kode_kurir ?? '-' }}
                                        </div>
                                    @else
                                        <span class="text-sm text-slate-400">-</span>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-10 text-center text-slate-400">
                                    Belum ada paket yang siap ditugaskan.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                    </table>
                </div>

                <div class="mt-5 border-t border-slate-100 pt-4">
                    <p class="text-sm text-slate-500">
                        Paket dipilih:
                        <span id="penugasan-selected-count"
                                class="font-bold text-[#0A3656]">
                            0 paket
                        </span>
                    </p>
                </div>

            </div>
        </div>

        {{-- Kolom Kanan: Daftar Kurir --}}
        <div class="col-span-4">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="mb-5">
                    <h2 class="text-base font-bold text-[#0A3656]">
                        Daftar Kurir Aktif
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        Maksimal 20 paket berstatus Dikirim.
                    </p>
                </div>

                {{-- Daftar Kurir --}}
                <div id="penugasan-kurir-list" class="space-y-3">
                @forelse ($kurir as $item)

                    @php
                        $kapasitasTerpakai = $item->paket_dikirim_count;
                        $sisaKapasitas = 20 - $kapasitasTerpakai;
                        $kapasitasPenuh = $sisaKapasitas <= 0;
                    @endphp

                    <label class="block rounded-xl border border-slate-200 p-4
                        {{ $kapasitasPenuh ? 'cursor-not-allowed bg-slate-50 opacity-60' : 'cursor-pointer hover:border-blue-400 hover:bg-blue-50/30' }}">

                        <div class="flex items-start gap-3">

                            <input
                                type="radio"
                                name="kurir_id"
                                value="{{ $item->id }}"
                                data-nama="{{ $item->pengguna->nama }}"
                                data-sisa="{{ $sisaKapasitas }}"
                                class="radio-kurir mt-1 rounded-full border-slate-300 text-[#0A3656]"
                                {{ $kapasitasPenuh ? 'disabled' : '' }}>

                            <div class="min-w-0 flex-1">

                                <div class="flex items-center justify-between gap-2">
                                    <h3 class="font-semibold text-[#0A3656]">
                                        {{ $item->pengguna->nama }}
                                    </h3>

                                    @if ($kapasitasPenuh)
                                        <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-600">
                                            Penuh
                                        </span>
                                    @else
                                        <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-600">
                                            Tersedia
                                        </span>
                                    @endif
                                </div>

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $item->kode_kurir }}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    Area: {{ $item->area_tugas }}
                                </p>

                                <div class="mt-3">
                                    <div class="mb-1 flex justify-between text-xs">
                                        <span class="text-slate-500">
                                            Kapasitas pengiriman
                                        </span>

                                        <span class="font-semibold text-[#0A3656]">
                                            {{ $kapasitasTerpakai }}/20 paket
                                        </span>
                                    </div>

                                    <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                        <div
                                            class="h-full rounded-full bg-[#1D70A2]"
                                            style="width: {{ min(($kapasitasTerpakai / 20) * 100, 100) }}%">
                                        </div>
                                    </div>

                                    <p class="mt-1 text-xs text-slate-500">
                                        Sisa {{ $sisaKapasitas }} kapasitas
                                    </p>
                                </div>

                            </div>

                        </div>

                    </label>

                @empty

                    <div class="rounded-lg bg-slate-50 px-4 py-8 text-center">
                        <p class="text-sm text-slate-400">
                            Belum ada kurir aktif.
                        </p>
                    </div>

                @endforelse

            </div>

                <div class="mt-5 border-t border-slate-100 pt-4">
                    <p class="text-sm text-slate-500">
                        Kurir dipilih:
                        <span id="penugasan-kurir-terpilih"
                                class="font-semibold text-[#0A3656]">
                            Belum ada
                        </span>
                    </p>

                    <p class="mt-2 text-sm text-slate-500">
                        Sisa kapasitas:
                        <span id="penugasan-sisa-kapasitas"
                                class="font-semibold text-[#0A3656]">
                            -
                        </span>
                    </p>
                </div>

                <button
                    type="submit"
                    id="btn-tugaskan-kurir"
                    disabled
                    class="mt-5 w-full rounded-lg bg-[#0A3656] px-4 py-3 text-sm font-semibold text-white opacity-50">
                    Tugaskan ke Kurir
                </button>

            </div>
        </div>

    </div>
</form>

@endsection

@push('scripts')
    @vite('resources/js/pages/penugasan-kurir.js')
@endpush