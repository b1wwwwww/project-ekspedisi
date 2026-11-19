@extends('layouts.manajer')

@section('title', 'Pendapatan per Zona')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#0A3656]">
        Pendapatan per Zona
    </h1>

    <p class="mt-1 text-sm text-slate-500">
        Rekap pendapatan ongkir dan biaya COD berdasarkan zona tujuan.
    </p>
</div>

{{-- Filter periode --}}
<div class="mb-6 rounded-xl bg-white p-5 shadow-sm">
    <h2 class="mb-4 font-semibold text-[#0A3656]">
        Filter Periode
    </h2>

    <form action="{{ route('manajer.laporan.pendapatan-zona') }}"
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
                class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
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
                class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>

        <button
            type="submit"
            class="rounded-lg bg-[#0A3656] px-5 py-2 text-sm font-semibold text-white hover:bg-[#082b45]">
            Terapkan Filter
        </button>

        <a
            href="{{ route('manajer.laporan.pendapatan-zona') }}"
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

{{-- Total pendapatan --}}
<div class="mb-6 rounded-xl bg-white p-5 shadow-sm">
    <p class="text-sm font-medium text-slate-500">
        Total Pendapatan
    </p>

    <h2 class="mt-2 text-3xl font-bold text-[#0A3656]">
        Rp{{ number_format($totalPendapatan, 0, ',', '.') }}
    </h2>

    <p class="mt-1 text-xs text-slate-400">
        Periode {{ $dari->format('d/m/Y') }} - {{ $sampai->format('d/m/Y') }}
    </p>
</div>

{{-- Tabel pendapatan --}}
<div class="overflow-hidden rounded-xl bg-white shadow-sm">
    <div class="border-b border-slate-100 px-5 py-4">
        <h2 class="font-semibold text-[#0A3656]">
            Rincian Pendapatan per Zona
        </h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-5 py-3">No.</th>
                    <th class="px-5 py-3">Zona</th>
                    <th class="px-5 py-3 text-right">Jumlah Paket</th>
                    <th class="px-5 py-3 text-right">Total Ongkir</th>
                    <th class="px-5 py-3 text-right">Biaya COD</th>
                    <th class="px-5 py-3 text-right">Total Pendapatan</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse ($rekap as $index => $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-5 py-4 font-medium text-slate-700">
                            {{ $item->nama_zona }}
                        </td>

                        <td class="px-5 py-4 text-right">
                            {{ number_format($item->jumlah_paket, 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 text-right">
                            Rp{{ number_format($item->total_ongkir, 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 text-right">
                            Rp{{ number_format($item->total_biaya_cod, 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 text-right font-semibold text-[#0A3656]">
                            Rp{{ number_format($item->total_ongkir + $item->total_biaya_cod, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-slate-400">
                            Tidak ada data pendapatan pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection