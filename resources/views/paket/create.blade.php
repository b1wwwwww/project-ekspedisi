@extends('layouts.admin')

@section('title', 'Input Paket')
@section('breadcrumb', 'Input Paket & Buat Resi')

@section('content')

    {{-- JUDUL HALAMAN --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#0A3656]">
            Input Paket &amp; Buat Resi
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Lengkapi data pengirim, penerima, dan paket.
            Ongkir &amp; biaya COD dihitung otomatis sesuai zona tujuan.
        </p>
    </div>

    {{-- RINGKASAN ERROR VALIDASI --}}
    @if ($errors->any() && !($errors->count() === 1 && $errors->has('zona_manual')))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
            <p class="mb-1 text-sm font-semibold text-red-700">
                Periksa kembali data berikut:
            </p>

            <ul class="list-disc space-y-0.5 pl-5 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    @continue($error === $errors->first('zona_manual'))
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('paket.store') }}"
            method="POST"
            id="form-input-paket">

        @csrf

        <div class="grid grid-cols-1 items-start gap-6 xl:grid-cols-12">

            {{-- ================================================= --}}
            {{-- KOLOM KIRI: FORMULIR PENGIRIMAN --}}
            {{-- ================================================= --}}

            <div class="space-y-6 xl:col-span-8">

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

                    <h2 class="mb-5 border-b border-slate-100 pb-4 text-base font-bold text-[#0A3656]">
                        Formulir Pengiriman Paket Baru
                    </h2>

                    {{-- A. DATA PENGIRIM --}}

                    {{-- Pilih Pelanggan Terdaftar --}}
                    <div class="mb-4">
                    <label for="pilih_pelanggan"
                        class="mb-2 block text-sm font-semibold text-slate-700">
                        Pilih Pelanggan Terdaftar
                    </label>

                    <select
                        id="pilih_pelanggan"
                        name="pelanggan_id"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">

                        <option value="">-- Pilih Pelanggan --</option>

                        @foreach ($pelanggan as $item)
                            <option
                                value="{{ $item->id }}"
                                data-nama="{{ $item->nama }}"
                                data-telepon="{{ $item->telepon }}"
                                data-alamat="{{ $item->alamat }}">

                                {{ $item->nama }} — {{ $item->telepon }}

                            </option>
                        @endforeach
                    </select>
                </div>

                    <div class="mb-3 grid grid-cols-1 gap-4 md:grid-cols-2">

                        <div>
                            <label for="nama_pengirim"
                                    class="mb-1.5 block text-[13px] font-semibold text-slate-700">
                                Nama Pengirim <span class="text-red-500">*</span>
                            </label>

                            <input type="text"
                                    id="nama_pengirim"
                                    name="nama_pengirim"
                                    value="{{ old('nama_pengirim') }}"
                                    class="w-full rounded-lg border px-3 py-2 text-sm outline-none focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]/30 {{ $errors->has('nama_pengirim') ? 'border-red-400' : 'border-slate-300' }}"
                                    placeholder="Nama pengirim / toko">

                            @error('nama_pengirim')
                                <p class="mt-1 text-[12px] text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="telepon_pengirim"
                                    class="mb-1.5 block text-[13px] font-semibold text-slate-700">
                                No. Telepon Pengirim <span class="text-red-500">*</span>
                            </label>

                            <input type="text"
                                    id="telepon_pengirim"
                                    name="telepon_pengirim"
                                    value="{{ old('telepon_pengirim') }}"
                                    class="w-full rounded-lg border px-3 py-2 text-sm outline-none focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]/30 {{ $errors->has('telepon_pengirim') ? 'border-red-400' : 'border-slate-300' }}"
                                    placeholder="08xxxxxxxxxx">

                            @error('telepon_pengirim')
                                <p class="mt-1 text-[12px] text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    <div class="mb-5">
                        <label for="alamat_pengirim"
                                class="mb-1.5 block text-[13px] font-semibold text-slate-700">
                            Alamat Pengirim
                            <span class="font-normal text-slate-400">(opsional)</span>
                        </label>

                        <textarea id="alamat_pengirim"
                                    name="alamat_pengirim"
                                    rows="2"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]/30"
                                    placeholder="Alamat pengirim">{{ old('alamat_pengirim') }}</textarea>

                        @error('alamat_pengirim')
                            <p class="mt-1 text-[12px] text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- B. DATA PENERIMA --}}
                    <div class="mb-3 grid grid-cols-1 gap-4 md:grid-cols-2">

                        <div>
                            <label for="penerima_nama"
                                    class="mb-1.5 block text-[13px] font-semibold text-slate-700">
                                Nama Penerima <span class="text-red-500">*</span>
                            </label>

                            <input type="text"
                                    id="penerima_nama"
                                    name="penerima_nama"
                                    value="{{ old('penerima_nama') }}"
                                    class="w-full rounded-lg border px-3 py-2 text-sm outline-none focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]/30 {{ $errors->has('penerima_nama') ? 'border-red-400' : 'border-slate-300' }}"
                                    placeholder="Nama penerima">

                            @error('penerima_nama')
                                <p class="mt-1 text-[12px] text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="penerima_telepon"
                                    class="mb-1.5 block text-[13px] font-semibold text-slate-700">
                                No. Telepon Penerima <span class="text-red-500">*</span>
                            </label>

                            <input type="text"
                                    id="penerima_telepon"
                                    name="penerima_telepon"
                                    value="{{ old('penerima_telepon') }}"
                                    class="w-full rounded-lg border px-3 py-2 text-sm outline-none focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]/30 {{ $errors->has('penerima_telepon') ? 'border-red-400' : 'border-slate-300' }}"
                                    placeholder="08xxxxxxxxxx">

                            @error('penerima_telepon')
                                <p class="mt-1 text-[12px] text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    <div class="mb-5">
                        <label for="penerima_alamat"
                                class="mb-1.5 block text-[13px] font-semibold text-slate-700">
                            Alamat Lengkap Penerima <span class="text-red-500">*</span>
                        </label>

                        <textarea id="penerima_alamat"
                                    name="penerima_alamat"
                                    rows="2"
                                    class="w-full rounded-lg border px-3 py-2 text-sm outline-none focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]/30 {{ $errors->has('penerima_alamat') ? 'border-red-400' : 'border-slate-300' }}"
                                    placeholder="Jl. ..., Kec. ..., Kota ...">{{ old('penerima_alamat') }}</textarea>

                        @error('penerima_alamat')
                            <p class="mt-1 text-[12px] text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- KOTA TUJUAN DAN STATUS ZONA --}}

                    <div class="mb-5 grid grid-cols-1 gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4 md:grid-cols-2">

                        <div>
                            <label for="kota_tujuan"
                                    class="mb-1.5 block text-[13px] font-semibold text-slate-700">
                                Kota / Kabupaten Tujuan <span class="text-red-500">*</span>
                            </label>

                            <input type="text"
                                    id="kota_tujuan"
                                    name="kota_tujuan"
                                    value="{{ old('kota_tujuan') }}"
                                    class="w-full rounded-lg border px-3 py-2 text-sm outline-none focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]/30 {{ $errors->has('kota_tujuan') ? 'border-red-400' : 'border-slate-300' }}"
                                    placeholder="Contoh: Kota Bandung">

                            @error('kota_tujuan')
                                <p class="mt-1 text-[12px] text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-[13px] font-semibold text-slate-700">
                                Status Zona
                            </label>

                            <div class="flex h-[38px] items-center">
                                <span id="status-zona-badge"
                                        class="rounded px-2.5 py-1 text-[12px] font-semibold {{ $errors->has('zona_manual') ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $errors->has('zona_manual') ? 'Manual (Perlu Verifikasi)' : 'Menunggu Perhitungan' }}
                                </span>
                            </div>

                            <p id="zona-helper-text"
                                class="mt-1.5 text-[11px] text-slate-500">
                                Zona dideteksi otomatis lewat API Pusat Wilayah berdasarkan kota tujuan.
                            </p>
                        </div>

                    </div>

                    {{-- C. INFORMASI PAKET --}}
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                        <div>
                            <label for="berat_kg"
                                    class="mb-1.5 block text-[13px] font-semibold text-slate-700">
                                Berat Aktual (kg) <span class="text-red-500">*</span>
                            </label>

                            <input type="number"
                                    step="0.01"
                                    min="0.01"
                                    id="berat_kg"
                                    name="berat_kg"
                                    value="{{ old('berat_kg') }}"
                                    class="w-full rounded-lg border px-3 py-2 text-sm outline-none focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]/30 {{ $errors->has('berat_kg') ? 'border-red-400' : 'border-slate-300' }}"
                                    placeholder="Contoh: 2.5">

                            <p class="mt-1 text-[11px] font-semibold text-[#1D70A2]">
                                Dibulatkan ke atas (min 1 kg):
                                <span id="berat-bulat-label">—</span>
                            </p>

                            @error('berat_kg')
                                <p class="mt-1 text-[12px] text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="layanan"
                                    class="mb-1.5 block text-[13px] font-semibold text-slate-700">
                                Jenis Layanan <span class="text-red-500">*</span>
                            </label>

                            <select id="layanan"
                                    name="layanan"
                                    class="w-full rounded-lg border px-3 py-2 text-sm outline-none focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]/30 {{ $errors->has('layanan') ? 'border-red-400' : 'border-slate-300' }}">

                                <option value="" disabled {{ old('layanan') ? '' : 'selected' }}>
                                    -- Pilih Layanan --
                                </option>

                                <option value="Reguler" {{ old('layanan') === 'Reguler' ? 'selected' : '' }}>
                                    Reguler (Tarif Dasar)
                                </option>

                                <option value="Express" {{ old('layanan') === 'Express' ? 'selected' : '' }}>
                                    Express (+50% dari Reguler)
                                </option>

                            </select>

                            @error('layanan')
                                <p class="mt-1 text-[12px] text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="nilai_cod"
                                    class="mb-1.5 block text-[13px] font-semibold text-slate-700">
                                Nilai Barang COD (Rp)
                            </label>

                            <input type="number"
                                    min="0"
                                    step="1000"
                                    id="nilai_cod"
                                    name="nilai_cod"
                                    value="{{ old('nilai_cod', 0) }}"
                                    class="w-full rounded-lg border px-3 py-2 text-sm outline-none focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]/30 {{ $errors->has('nilai_cod') ? 'border-red-400' : 'border-slate-300' }}">

                            <p class="mt-1 text-[11px] text-slate-500">
                                Isi &gt; 0 jika COD. Biarkan <b>0</b> jika Non-COD.
                            </p>

                            @error('nilai_cod')
                                <p class="mt-1 text-[12px] text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>


            {{-- ================================================= --}}
            {{-- KOLOM KANAN: RINCIAN BIAYA --}}
            {{-- ================================================= --}}

            <div class="space-y-5 xl:col-span-4">

                {{-- RINCIAN BIAYA DAN RESI --}}

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

                    <h2 class="mb-4 border-b border-slate-100 pb-4 text-base font-bold text-[#0A3656]">
                        Rincian Biaya &amp; Resi
                    </h2>

                    <div class="mb-4 space-y-2.5 rounded-xl border border-slate-200 bg-slate-50 p-4 text-[13px]">

                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500">Zona Tujuan:</span>
                            <span id="ringkasan_zona" class="font-semibold text-slate-800">—</span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500">Berat Aktual &rarr; Tagih:</span>
                            <span id="ringkasan_berat" class="font-semibold text-slate-800">—</span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500">Tarif per Kg:</span>
                            <span id="ringkasan_tarif" class="font-semibold text-slate-800">—</span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500">Ongkos Kirim:</span>
                            <span id="ringkasan_ongkir" class="font-semibold text-slate-800">—</span>
                        </div>

                        <div class="flex justify-between gap-3">
                            <span class="text-slate-500">Biaya Layanan COD:</span>
                            <span id="ringkasan_cod" class="font-semibold text-slate-800">—</span>
                        </div>

                        <div class="flex items-center justify-between gap-3 border-t border-slate-200 pt-2.5">
                            <span class="text-[14px] font-bold text-[#0A3656]">
                                Total Bayar di Loket:
                            </span>

                            <span id="ringkasan_total"
                                    class="text-[17px] font-bold text-[#0A3656]">
                                —
                            </span>
                        </div>

                    </div>


                    {{-- FALLBACK ZONA MANUAL --}}

                    <div id="pilihan_zona_manual"
                            class="{{ $errors->has('zona_manual') ? '' : 'hidden' }} mb-4 rounded-lg border border-amber-200 bg-amber-50 p-3">

                        <label for="zona_manual"
                                class="mb-1.5 block text-[12px] font-semibold text-amber-800">
                            API Pusat Wilayah tidak dapat menentukan zona.
                            Pilih zona tujuan secara manual.
                        </label>

                        <select id="zona_manual"
                                name="zona_manual"
                                class="w-full rounded-lg border border-amber-300 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-amber-300">

                            <option value="">-- Pilih Zona --</option>

                            @foreach ($zonaTarif as $zona)
                                <option value="{{ $zona->id }}"
                                    {{ (string) old('zona_manual') === (string) $zona->id ? 'selected' : '' }}>

                                    {{ $zona->nama_zona }}
                                    — Rp{{ number_format($zona->harga_per_kg, 0, ',', '.') }}/kg

                                </option>
                            @endforeach

                        </select>

                        @error('zona_manual')
                            <p class="mt-1.5 text-[11px] text-amber-700">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- PESAN PERHITUNGAN --}}

                    <p id="pesan_perhitungan"
                        class="mb-4 text-[12px] text-slate-500">
                        Lengkapi kota tujuan, berat, dan layanan untuk melihat estimasi tarif.
                    </p>


                    {{-- TOMBOL SIMPAN --}}

                    <button type="submit"
                            class="w-full rounded-lg bg-[#0A3656] py-3 text-[14px] font-semibold text-white transition hover:bg-[#1D70A2] disabled:cursor-not-allowed disabled:opacity-60">
                        Simpan Paket &amp; Buat Resi
                    </button>

                    <a href="{{ route('paket.index') }}"
                        class="mt-2.5 block text-center text-[13px] font-semibold text-slate-500 hover:text-slate-700">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>

    <div id="konfigurasi-paket"
        data-url-hitung-tarif="{{ route('paket.hitung-tarif') }}">
    </div>

    @push('scripts')
        @vite('resources/js/pages/paket-create.js')
    @endpush

@endsection