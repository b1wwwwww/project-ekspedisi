@extends('layouts.admin')

@section('title', 'Detail Paket')
@section('breadcrumb', 'Detail Paket')

@section('content')

    {{-- Header Halaman --}}
    <div class="mb-6">
        <a href="{{ route('paket.index') }}"
            class="text-sm font-semibold text-[#1D70A2] hover:underline">
            ← Kembali ke Daftar Paket
        </a>

        <div class="flex flex-wrap items-center justify-between gap-3 mt-4">
            <div>
                <h1 class="text-2xl font-bold text-[#0A3656]">
                    Detail Paket
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    Informasi lengkap pengiriman nomor resi
                    {{ $paket->no_resi }}.
                </p>
            </div>

            <span class="px-4 py-2 rounded-lg text-sm font-semibold bg-blue-100 text-blue-700">
                {{ $paket->status }}
            </span>
        </div>
    </div>

    {{-- Ringkasan Paket --}}

        {{-- Informasi Pengirim dan Penerima --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mt-5">

        {{-- Pengirim --}}
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <h2 class="text-lg font-bold text-[#0A3656] mb-4">
                Informasi Pengirim
            </h2>

            <div class="space-y-4">
                <div>
                    <p class="text-xs text-slate-500 mb-1">Nama Pengirim</p>
                    <p class="font-semibold text-slate-700">
                        {{ $paket->pengirim?->nama ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-slate-500 mb-1">Nomor Telepon</p>
                    <p class="font-semibold text-slate-700">
                        {{ $paket->pengirim?->telepon ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-slate-500 mb-1">Alamat Pengirim</p>
                    <p class="font-semibold text-slate-700">
                        {{ $paket->pengirim?->alamat ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Penerima --}}
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
            <h2 class="text-lg font-bold text-[#0A3656] mb-4">
                Informasi Penerima
            </h2>

            <div class="space-y-4">
                <div>
                    <p class="text-xs text-slate-500 mb-1">Nama Penerima</p>
                    <p class="font-semibold text-slate-700">
                        {{ $paket->penerima_nama }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-slate-500 mb-1">Nomor Telepon</p>
                    <p class="font-semibold text-slate-700">
                        {{ $paket->penerima_telepon }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-slate-500 mb-1">Alamat Penerima</p>
                    <p class="font-semibold text-slate-700">
                        {{ $paket->penerima_alamat }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Informasi Tarif dan Pembayaran --}}
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm mt-5">

            <h2 class="text-lg font-bold text-[#0A3656] mb-5">
                Informasi Tarif dan Pembayaran
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                <div>
                    <p class="text-xs text-slate-500 mb-1">Berat Aktual</p>
                    <p class="font-semibold text-slate-700">
                        {{ $paket->berat_kg }} kg
                    </p>
                </div>

                <div>
                    <p class="text-xs text-slate-500 mb-1">Berat Tagih</p>
                    <p class="font-semibold text-slate-700">
                        {{ $paket->berat_tagih }} kg
                    </p>
                </div>

                <div>
                    <p class="text-xs text-slate-500 mb-1">Tarif per Kg</p>
                    <p class="font-semibold text-slate-700">
                        Rp{{ number_format($paket->tarif_per_kg, 0, ',', '.') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-slate-500 mb-1">Ongkos Kirim</p>
                    <p class="font-bold text-[#1D70A2]">
                        Rp{{ number_format($paket->ongkir, 0, ',', '.') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-slate-500 mb-1">Nilai COD</p>
                    <p class="font-semibold text-slate-700">
                        Rp{{ number_format($paket->nilai_cod, 0, ',', '.') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-slate-500 mb-1">Biaya COD</p>
                    <p class="font-semibold text-slate-700">
                        Rp{{ number_format($paket->biaya_cod, 0, ',', '.') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-slate-500 mb-1">Layanan</p>
                    <p class="font-semibold text-slate-700">
                        {{ $paket->layanan }}
                    </p>
                </div>
            </div>
        </div>


        {{-- Informasi Kurir --}}
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm mt-5">

            <h2 class="text-lg font-bold text-[#0A3656] mb-4">
                Informasi Kurir
            </h2>

            @if ($paket->kurir)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <p class="text-xs text-slate-500 mb-1">Kode Kurir</p>
                        <p class="font-semibold text-slate-700">
                            {{ $paket->kurir->kode_kurir }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-500 mb-1">Nama Kurir</p>
                        <p class="font-semibold text-slate-700">
                            {{ $paket->kurir->pengguna?->nama ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-500 mb-1">Area Tugas</p>
                        <p class="font-semibold text-slate-700">
                            {{ $paket->kurir->area_tugas ?? '-' }}
                        </p>
                    </div>

                </div>
            @else
                <p class="text-sm text-slate-500">
                    Paket ini belum ditugaskan kepada kurir.
                </p>
            @endif
        </div>

        {{-- Riwayat Perjalanan Paket --}}
        <div class="mt-5 w-full rounded-xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">

            <h2 class="mb-2 text-lg font-bold text-[#0A3656]">
                Riwayat Perjalanan Paket
            </h2>

            <p class="mb-6 text-sm text-slate-500">
                Riwayat perubahan status pengiriman paket.
            </p>

            @if ($paket->riwayat->isNotEmpty())
                <div class="w-full overflow-x-auto pb-2">
                    <div class="flex min-w-[640px] items-start gap-5">
                        @foreach ($paket->riwayat as $riwayat)
                            @php
                                $statusStyle = match ($riwayat->status) {
                                    'Diterima' => ['dot' => 'bg-blue-600', 'badge' => 'bg-blue-100 text-blue-700'],
                                    'Transit' => ['dot' => 'bg-amber-500', 'badge' => 'bg-amber-100 text-amber-700'],
                                    'Dikirim' => ['dot' => 'bg-indigo-500', 'badge' => 'bg-indigo-100 text-indigo-700'],
                                    'Terkirim' => ['dot' => 'bg-emerald-500', 'badge' => 'bg-emerald-100 text-emerald-700'],
                                    'Gagal Kirim' => ['dot' => 'bg-red-500', 'badge' => 'bg-red-100 text-red-700'],
                                    'Retur' => ['dot' => 'bg-slate-600', 'badge' => 'bg-slate-200 text-slate-700'],
                                    default => ['dot' => 'bg-slate-400', 'badge' => 'bg-slate-100 text-slate-700'],
                                };
                            @endphp

                            <div class="w-[280px] shrink-0">
                                <div class="mb-4 flex items-center">
                                    <span class="h-3 w-3 rounded-full {{ $statusStyle['dot'] }} ring-3 ring-white shadow-sm"></span>

                                    @unless ($loop->last)
                                        <span class="ml-3 h-0.5 flex-1 bg-slate-200"></span>
                                    @endunless
                                </div>

                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                    <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold {{ $statusStyle['badge'] }}">
                                        {{ $riwayat->status }}
                                    </span>

                                    <p class="mt-3 text-sm font-semibold text-slate-700">
                                        {{ $riwayat->created_at->format('d M Y, H:i') }}
                                    </p>

                                    <p class="mt-2 text-xs text-slate-500">
                                        Petugas: {{ $riwayat->pengguna?->nama ?? 'Tidak diketahui' }}
                                    </p>

                                    <p class="mt-2 text-sm text-slate-600">
                                        {{ $riwayat->catatan ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-sm text-slate-500">
                    Belum ada riwayat perjalanan paket.
                </p>
            @endif
        </div>
    </div>
@endsection