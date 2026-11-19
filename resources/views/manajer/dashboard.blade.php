@extends('layouts.manajer')

@section('title', 'Dashboard Manajer')

@section('breadcrumb', 'Dashboard Manajer')

@section('content')

    {{-- Welcome Manager --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">
            Selamat Datang, {{ auth()->user()->nama }}
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Kelola tarif, pantau operasional, dan lihat data ekspedisi Priangan Kilat Express.
        </p>
    </div>

    {{-- Statistik Operasional --}}
    <div class="mb-8">
        <h2 class="mb-2 text-lg font-bold text-slate-800">
            Statistik Operasional
        </h2>

        <p class="mb-4 text-sm text-slate-500">
            Data keseluruhan paket per {{ now()->translatedFormat('d F Y') }}.
        </p>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Paket</p>
                <h3 class="mt-2 text-2xl font-bold text-slate-800">
                    {{ number_format($totalPaket, 0, ',', '.') }}
                </h3>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Paket Terkirim</p>
                <h3 class="mt-2 text-2xl font-bold text-green-600">
                    {{ number_format($paketTerkirim, 0, ',', '.') }}
                </h3>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Dalam Proses</p>
                <h3 class="mt-2 text-2xl font-bold text-blue-600">
                    {{ number_format($paketDalamProses, 0, ',', '.') }}
                </h3>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Gagal Kirim</p>
                <h3 class="mt-2 text-2xl font-bold text-orange-600">
                    {{ number_format($paketGagal, 0, ',', '.') }}
                </h3>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Retur</p>
                <h3 class="mt-2 text-2xl font-bold text-red-600">
                    {{ number_format($paketRetur, 0, ',', '.') }}
                </h3>
            </div>

        </div>
    </div>

    {{-- Ringkasan Keuangan --}}
    <div class="mb-8">
        <h2 class="mb-2 text-lg font-bold text-slate-800">
            Ringkasan Keuangan Bulan Ini
        </h2>

        <p class="mb-4 text-sm text-slate-500">
            Periode {{ now()->translatedFormat('F Y') }}.
        </p>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Pendapatan Ongkir
                </p>
                <h3 class="mt-2 text-xl font-bold text-slate-800">
                    Rp{{ number_format($totalPendapatanOngkir, 0, ',', '.') }}
                </h3>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Biaya COD
                </p>
                <h3 class="mt-2 text-xl font-bold text-blue-600">
                    Rp{{ number_format($totalBiayaCod, 0, ',', '.') }}
                </h3>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    COD Sudah Disetor
                </p>
                <h3 class="mt-2 text-xl font-bold text-green-600">
                    Rp{{ number_format($totalCodDisetor, 0, ',', '.') }}
                </h3>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    COD Belum Disetor
                </p>
                <h3 class="mt-2 text-xl font-bold text-orange-600">
                    Rp{{ number_format($totalCodBelumDisetor, 0, ',', '.') }}
                </h3>
            </div>

        </div>
    </div>

    {{-- Akses Cepat --}}
    <div>
        <h2 class="mb-4 text-lg font-bold text-slate-800">
            Akses Cepat
        </h2>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">

            {{-- Manajemen Tarif --}}
            <a href="{{ route('tarif.index') }}"
                class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <h3 class="text-lg font-semibold text-slate-800">
                    Manajemen Tarif & Zona
                </h3>
                <p class="mt-2 text-sm text-slate-500">
                    Lihat dan kelola tarif pengiriman berdasarkan zona wilayah.
                </p>
                <span class="mt-4 inline-block text-sm font-semibold text-blue-600">
                    Buka Manajemen Tarif →
                </span>
            </a>

            {{-- Data Pelanggan --}}
            <a href="{{ route('pelanggan.index') }}"
                class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <h3 class="text-lg font-semibold text-slate-800">
                    Data Pelanggan
                </h3>
                <p class="mt-2 text-sm text-slate-500">
                    Lihat data pelanggan yang terdaftar dalam sistem.
                </p>
                <span class="mt-4 inline-block text-sm font-semibold text-blue-600">
                    Lihat Data Pelanggan →
                </span>
            </a>

            {{-- Data Kurir --}}
            <a href="{{ route('admin.kurir.index') }}"
                class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <h3 class="text-lg font-semibold text-slate-800">
                    Data Kurir
                </h3>
                <p class="mt-2 text-sm text-slate-500">
                    Lihat daftar kurir yang terdaftar dalam sistem.
                </p>
                <span class="mt-4 inline-block text-sm font-semibold text-blue-600">
                    Lihat Data Kurir →
                </span>
            </a>

            {{-- Laporan Pengiriman --}}
            <a href="{{ route('manajer.laporan.pengiriman') }}"
                class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <h3 class="text-lg font-semibold text-slate-800">
                    Laporan Pengiriman
                </h3>
                <p class="mt-2 text-sm text-slate-500">
                    Lihat rekap jumlah paket berdasarkan status pengiriman.
                </p>
                <span class="mt-4 inline-block text-sm font-semibold text-blue-600">
                    Lihat Laporan →
                </span>
            </a>

            {{-- Pendapatan per Zona --}}
            <a href="{{ route('manajer.laporan.pendapatan-zona') }}"
                class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <h3 class="text-lg font-semibold text-slate-800">
                    Pendapatan per Zona
                </h3>
                <p class="mt-2 text-sm text-slate-500">
                    Pantau pendapatan pengiriman berdasarkan zona tujuan.
                </p>
                <span class="mt-4 inline-block text-sm font-semibold text-blue-600">
                    Lihat Laporan →
                </span>
            </a>

            {{-- Rekap COD --}}
            <a href="{{ route('manajer.laporan.rekap-cod') }}"
                class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <h3 class="text-lg font-semibold text-slate-800">
                    Rekap COD per Kurir
                </h3>
                <p class="mt-2 text-sm text-slate-500">
                    Pantau jumlah COD yang sudah dan belum disetorkan.
                </p>
                <span class="mt-4 inline-block text-sm font-semibold text-blue-600">
                    Lihat Rekap COD →
                </span>
            </a>

        </div>
    </div>

@endsection