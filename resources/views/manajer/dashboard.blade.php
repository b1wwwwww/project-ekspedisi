@extends('layouts.manajer')

@section('title', 'Dashboard Manajer')

@section('breadcrumb', 'Dashboard Manajer')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">
            Selamat Datang, {{ auth()->user()->name }}
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Kelola tarif, pantau operasional, dan lihat data ekspedisi Priangan Kilat Express.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <a href="{{ route('tarif.index') }}"
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
            <h2 class="text-lg font-semibold text-slate-800">
                Manajemen Tarif & Zona
            </h2>

            <p class="mt-2 text-sm text-slate-500">
                Lihat dan kelola tarif pengiriman berdasarkan zona wilayah.
            </p>

            <span class="mt-4 inline-block text-sm font-semibold text-blue-600">
                Buka Manajemen Tarif →
            </span>
        </a>

        <a href="{{ route('pelanggan.index') }}"
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
            <h2 class="text-lg font-semibold text-slate-800">
                Data Pelanggan
            </h2>

            <p class="mt-2 text-sm text-slate-500">
                Lihat data pelanggan yang terdaftar dalam sistem.
            </p>

            <span class="mt-4 inline-block text-sm font-semibold text-blue-600">
                Lihat Data Pelanggan →
            </span>
        </a>

        <a href="{{ route('admin.kurir.index') }}"
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">
            <h2 class="text-lg font-semibold text-slate-800">
                Data Kurir
            </h2>

            <p class="mt-2 text-sm text-slate-500">
                Lihat daftar kurir yang terdaftar dalam sistem.
            </p>

            <span class="mt-4 inline-block text-sm font-semibold text-blue-600">
                Lihat Data Kurir →
            </span>
        </a>
    </div>
@endsection