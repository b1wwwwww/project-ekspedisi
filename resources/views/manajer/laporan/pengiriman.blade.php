@extends('layouts.manajer')

@section('title', 'Laporan Pengiriman')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#0A3656]">
        Laporan Pengiriman
    </h1>

    <p class="mt-1 text-sm text-slate-500">
        Rekap jumlah paket berdasarkan status dalam periode tertentu.
    </p>
</div>

{{-- Filter tanggal --}}
<div class="mb-6 rounded-xl bg-white p-5 shadow-sm">
    <h2 class="mb-4 font-semibold text-[#0A3656]">
        Filter Periode
    </h2>

    {{-- Tanggal dikirim melalui URL, sehingga hasil filter bisa di-refresh atau dibagikan. --}}
    <form action="{{ route('manajer.laporan.pengiriman') }}"
            method="GET"
            class="flex flex-wrap items-end gap-4">

        <div>
            <label for="dari" class="mb-1 block text-sm font-medium text-slate-600">
                Tanggal Awal
            </label>

            <input
                type="date"
                name="dari"
                id="dari"
                value="{{ request('dari', $dari->format('Y-m-d')) }}"
                class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#0A3656] focus:outline-none">
        </div>

        <div>
            <label for="sampai" class="mb-1 block text-sm font-medium text-slate-600">
                Tanggal Akhir
            </label>

            <input
                type="date"
                name="sampai"
                id="sampai"
                value="{{ request('sampai', $sampai->format('Y-m-d')) }}"
                class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-[#0A3656] focus:outline-none">
        </div>

        <button
            type="submit"
            class="rounded-lg bg-[#0A3656] px-5 py-2 text-sm font-semibold text-white hover:bg-[#082b45]">
            Terapkan Filter
        </button>

        <a
            href="{{ route('manajer.laporan.pengiriman') }}"
            class="rounded-lg border border-slate-300 px-5 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
            Reset
        </a>
    </form>

    @if ($errors->any())
        <div class="mt-4 rounded-lg bg-red-50 p-3 text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
</div>

{{-- Ringkasan total paket --}}
<div class="mb-6 rounded-xl bg-white p-5 shadow-sm">
    <p class="text-sm font-medium text-slate-500">
        Total Paket
    </p>

    {{-- Mengubah angka menjadi format Indonesia. Contoh: 1500 menjadi 1.500. --}}
    <h2 class="mt-2 text-3xl font-bold text-[#0A3656]">
        {{ number_format($totalPaket, 0, ',', '.') }}
    </h2>

    <p class="mt-1 text-xs text-slate-400">
        Periode {{ $dari->format('d/m/Y') }} - {{ $sampai->format('d/m/Y') }}
    </p>
</div>

{{-- Tabel laporan --}}
<div class="overflow-hidden rounded-xl bg-white shadow-sm">
    <div class="border-b border-slate-100 px-5 py-4">
        <h2 class="font-semibold text-[#0A3656]">
            Rekap Paket Berdasarkan Status
        </h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-5 py-3">No.</th>
                    <th class="px-5 py-3">Status Paket</th>
                    <th class="px-5 py-3 text-right">Jumlah Paket</th>
                </tr>
            </thead>

            {{-- Jika ada data, tampilkan setiap status.
                Jika tidak ada, tampilkan pesan kosong melalui @empty. --}}
            <tbody class="divide-y divide-slate-100">
                @forelse ($rekap as $index => $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-5 py-4 font-medium text-slate-700">
                            {{ $item->status }}
                        </td>

                        <td class="px-5 py-4 text-right font-semibold text-[#0A3656]">
                            {{ number_format($item->jumlah, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-5 py-10 text-center text-slate-400">
                            Tidak ada data paket pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection