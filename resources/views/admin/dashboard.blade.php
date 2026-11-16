@extends('layouts.admin')

@section('title', 'Dashboard Operasional')
@section('breadcrumb', 'Dashboard Admin')

@section('content')

    {{-- Judul Dashboard --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#0A3656]">
            Dashboard Operasional
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Ringkasan aktivitas operasional Priangan Kilat Express.
        </p>
    </div>

    {{-- Kartu KPI --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-[15px] font-medium text-slate-600">Paket Masuk Hari Ini</p>
            <h2 class="mt-4 flex items-end gap-2 leading-none text-[#0A3656]">
                <span class="text-[clamp(2rem,2.4vw,3rem)] font-bold tracking-[-0.04em]">{{ $paketMasukHariIni }}</span>
                <span class="mb-1 text-[1.05rem] font-semibold">Paket</span>
            </h2>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-[15px] font-medium text-slate-600">Menunggu Penugasan</p>
            <h2 class="mt-4 flex items-end gap-2 leading-none text-amber-600">
                <span class="text-[clamp(2rem,2.4vw,3rem)] font-bold tracking-[-0.04em]">{{ $menungguPenugasan }}</span>
                <span class="mb-1 text-[1.05rem] font-semibold">Paket</span>
            </h2>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-[15px] font-medium text-slate-600">Sedang Dikirim</p>
            <h2 class="mt-4 flex items-end gap-2 leading-none text-blue-600">
                <span class="text-[clamp(2rem,2.4vw,3rem)] font-bold tracking-[-0.04em]">{{ $sedangDiKirim }}</span>
                <span class="mb-1 text-[1.05rem] font-semibold">Paket</span>
            </h2>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-[15px] font-medium text-slate-600">Perlu Tindakan Retur</p>
            <h2 class="mt-4 flex items-end gap-2 leading-none text-red-600">
                <span class="text-[clamp(2rem,2.4vw,3rem)] font-bold tracking-[-0.04em]">{{ $perluTindakanRetur }}</span>
                <span class="mb-1 text-[1.05rem] font-semibold">Paket</span>
            </h2>
        </div>

    </div>

    {{-- Bagian Utama Dashboard --}}
    <div class="mt-8 grid grid-cols-1 items-start gap-6 xl:grid-cols-3">

        {{-- Antrean Operasional --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm xl:col-span-2">

            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
                <div>
                    <h2 class="text-lg font-bold text-[#0A3656]">
                        Antrean Tindakan Operasional
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Daftar paket yang membutuhkan tindakan Admin.
                    </p>
                </div>

                <a href="{{ route('paket.index') }}"
                    class="whitespace-nowrap text-sm font-semibold text-[#1D70A2] hover:underline">
                    Lihat Semua →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">

                    <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                        <tr>
                            <th class="px-6 py-4 font-semibold">No. Resi</th>
                            <th class="px-6 py-4 font-semibold">Penerima & Tujuan</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Tindakan</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($antreanOperasional as $paket)

                            <tr class="transition hover:bg-slate-50">

                                <td class="whitespace-nowrap px-6 py-4 font-semibold text-[#0A3656]">
                                    {{ $paket->no_resi }}
                                </td>

                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-800">
                                        {{ $paket->penerima_nama }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $paket->kota_tujuan }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    @if ($paket->status === 'Diterima')
                                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                            Diterima
                                        </span>
                                    @elseif ($paket->status === 'Transit')
                                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Transit
                                        </span>
                                    @elseif ($paket->status === 'Gagal Kirim')
                                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            Gagal Kirim ({{ $paket->jumlah_gagal }}x)
                                        </span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    @if ($paket->status === 'Diterima')
                                        <a href="{{ route('paket.index') }}"
                                            class="font-semibold text-[#1D70A2] hover:underline">
                                            Proses Transit
                                        </a>
                                    @elseif ($paket->status === 'Transit')
                                        <a href="{{ route('paket.index') }}"
                                            class="font-semibold text-[#1D70A2] hover:underline">
                                            Tugaskan Kurir
                                        </a>
                                    @elseif ($paket->status === 'Gagal Kirim')
                                        <a href="{{ route('paket.index') }}"
                                            class="font-semibold text-red-600 hover:underline">
                                            Proses Retur
                                        </a>
                                    @endif
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                    Belum ada paket yang membutuhkan tindakan operasional.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="space-y-6">

            {{-- Kapasitas Kurir --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="mb-5">
                    <h2 class="text-lg font-bold text-[#0A3656]">
                        Kapasitas Kurir Aktif
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Maksimal 20 paket per kurir.
                    </p>
                </div>

                <div class="space-y-5">

                    @forelse ($kapasitasKurir as $kurir)

                        @php
                            $jumlahPaket = $kurir->paket_dikirim_count;
                            $persentase = min(($jumlahPaket / 20) * 100, 100);
                        @endphp

                        <div>
                            <div class="mb-2 flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $kurir->pengguna->name ?? 'Kurir' }}
                                    </p>
                                    <p class="text-xs text-slate-500">
                                        {{ $kurir->kode_kurir }}
                                    </p>
                                </div>

                                <span class="whitespace-nowrap text-xs font-semibold text-slate-600">
                                    {{ $jumlahPaket }}/20
                                </span>
                            </div>

                            <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-[#1D70A2]"
                                    style="width: {{ $persentase }}%">
                                </div>
                            </div>

                            <p class="mt-1 text-right text-xs text-slate-400">
                                {{ $jumlahPaket >= 20 ? 'Kapasitas Penuh' : 'Tersedia ' . (20 - $jumlahPaket) . ' slot' }}
                            </p>
                        </div>

                    @empty
                        <p class="py-4 text-center text-sm text-slate-400">
                            Belum ada kurir aktif.
                        </p>
                    @endforelse

                </div>
            </div>

            {{-- COD Belum Disetor --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="text-lg font-bold text-[#0A3656]">
                    COD Belum Disetor
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Total COD dari paket yang sudah terkirim tetapi belum dicatat setorannya.
                </p>

                <h3 class="mt-5 text-2xl font-bold text-amber-600">
                    Rp{{ number_format($codBelumDisetor, 0, ',', '.') }}
                </h3>

                <p class="mt-2 text-xs text-slate-400">
                    Nominal yang perlu dicatat oleh Admin.
                </p>

            </div>

        </div>

    </div>

@endsection