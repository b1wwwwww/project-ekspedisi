@extends('layouts.manajer')

@section('title', 'Rekap COD per Kurir')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#0A3656]">
        Rekap COD per Kurir
    </h1>

    <p class="mt-1 text-sm text-slate-500">
        Ringkasan COD yang sudah dan belum disetorkan oleh setiap kurir.
    </p>
</div>

{{-- Ringkasan COD --}}
<div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2">

    <div class="rounded-xl bg-white p-5 shadow-sm">
        <p class="text-sm font-medium text-slate-500">
            Total COD Sudah Disetor
        </p>

        <h2 class="mt-2 text-2xl font-bold text-green-600">
            Rp{{ number_format($kurir->sum('cod_sudah_disetor'), 0, ',', '.') }}
        </h2>
    </div>

    <div class="rounded-xl bg-white p-5 shadow-sm">
        <p class="text-sm font-medium text-slate-500">
            Total COD Belum Disetor
        </p>

        <h2 class="mt-2 text-2xl font-bold text-orange-500">
            Rp{{ number_format($kurir->sum('cod_belum_disetor'), 0, ',', '.') }}
        </h2>
    </div>

</div>

{{-- Tabel Rekap COD --}}
<div class="overflow-hidden rounded-xl bg-white shadow-sm">

    <div class="border-b border-slate-100 px-5 py-4">
        <h2 class="font-semibold text-[#0A3656]">
            Rincian COD Setiap Kurir
        </h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">

            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-5 py-3">No.</th>
                    <th class="px-5 py-3">Nama Kurir</th>
                    <th class="px-5 py-3">Kode Kurir</th>
                    <th class="px-5 py-3 text-right">Sudah Disetor</th>
                    <th class="px-5 py-3 text-right">Belum Disetor</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">

                @forelse ($kurir as $index => $item)

                    <tr class="hover:bg-slate-50">

                        <td class="px-5 py-4">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-5 py-4 font-medium text-slate-700">
                            {{ $item->pengguna->nama ?? 'Pengguna tidak ditemukan' }}
                        </td>

                        <td class="px-5 py-4 text-slate-600">
                            {{ $item->kode_kurir }}
                        </td>

                        <td class="px-5 py-4 text-right font-semibold text-green-600">
                            Rp{{ number_format($item->cod_sudah_disetor, 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 text-right font-semibold text-orange-500">
                            Rp{{ number_format($item->cod_belum_disetor ?? 0, 0, ',', '.') }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-slate-400">
                            Belum ada data kurir.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>

</div>

@endsection