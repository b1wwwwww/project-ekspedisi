<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Priangan Kilat Express — Ekspedisi Cepat & Terpercaya</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Figtree', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased flex flex-col justify-between">

    <div>
        {{-- ===== NAVBAR ===== --}}
        <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8 py-3.5">
                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#0A3656] text-xl font-bold text-white shadow-sm transition group-hover:scale-105">
                        ⚡
                    </div>
                    <div>
                        <span class="text-base font-extrabold tracking-tight text-[#0A3656]">Priangan Kilat Express</span>
                    </div>
                </a>

                {{-- Menu Navigasi Desktop --}}
                <nav class="flex items-center gap-6 sm:gap-8 text-sm font-semibold text-slate-600">
                    <a href="{{ url('/') }}" class="transition hover:text-[#1D70A2]">Beranda</a>
                    <a href="#cek-resi" class="transition hover:text-[#1D70A2]">Cek Resi</a>
                    <a href="#tarif-zona" class="transition hover:text-[#1D70A2]">Tarif Zona</a>
                    <a href="#tentang-kami" class="transition hover:text-[#1D70A2]">Tentang Kami</a>
                </nav>
            </div>
        </header>

        {{-- ===== HERO SECTION DENGAN FORM CEK RESI ===== --}}
        <section id="cek-resi" class="relative overflow-hidden text-white pt-16 pb-28 px-4 sm:px-6 lg:px-8"
                 style="background: linear-gradient(180deg, #093c61 0%, #0d4f7e 45%, #156296 80%, #1d70a2 100%);">

            {{-- Dekorasi Geometris Halus --}}
            <div class="absolute -top-24 -left-24 h-96 w-96 rounded-full opacity-10 bg-white blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 h-96 w-96 rounded-full opacity-10 bg-cyan-300 blur-2xl pointer-events-none"></div>

            <div class="relative z-10 mx-auto max-w-4xl text-center">
                {{-- Judul Hero --}}
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight leading-tight">
                    Kirim Paket Cepat & Aman di Bandung Raya
                </h1>
                <p class="mt-4 text-sm sm:text-base md:text-lg text-blue-100/90 max-w-2xl mx-auto leading-relaxed">
                    Solusi ekspedisi terpercaya pilihan toko online dan UMKM lokal dengan tarif transparan serta layanan COD terbaik.
                </p>

                {{-- Box Pencarian Resi --}}
                <div class="mt-10 mx-auto max-w-2xl rounded-2xl bg-white p-5 sm:p-7 text-left shadow-2xl border border-white/20">
                    <label for="resi-input" class="flex items-center gap-2 text-sm sm:text-base font-bold text-[#0A3656]">
                        <svg class="h-5 w-5 text-[#1D70A2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Lacak Resi Pengiriman Anda Secara Instan</span>
                    </label>

                    <form method="GET" action="{{ route('pelacakan-resi.index') }}#hasil-lacak" class="mt-3.5 flex flex-col sm:flex-row gap-3">
                        <input
                            id="resi-input"
                            type="text"
                            name="resi"
                            value="{{ $resi }}"
                            required
                            autocomplete="off"
                            placeholder="Masukkan nomor resi (cth: PKE-261109-0001)"
                            class="min-w-0 flex-1 rounded-xl border border-slate-300 px-4 py-3.5 text-sm text-slate-800 placeholder-slate-400 uppercase tracking-wide transition focus:border-[#1D70A2] focus:outline-none focus:ring-2 focus:ring-[#1D70A2]/30"
                        >
                        <button
                            type="submit"
                            class="rounded-xl px-7 py-3.5 text-sm font-bold text-white shadow-md transition hover:brightness-105 active:scale-95 whitespace-nowrap"
                            style="background: #e58a00;"
                        >
                            Lacak Paket
                        </button>
                    </form>

                    @error('resi')
                        <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        {{-- ===== HASIL PELACAKAN RESI (JIKA DICARI) ===== --}}
        @if ($resi !== '')
            <section id="hasil-lacak" class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 -mt-14 mb-10 relative z-20">
                @if (! $paket)
                    {{-- Alert Tidak Ditemukan --}}
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-center text-amber-900 shadow-lg">
                        <svg class="mx-auto h-8 w-8 text-amber-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <h3 class="text-lg font-bold">Nomor Resi Tidak Ditemukan</h3>
                        <p class="mt-1 text-sm text-amber-800">
                            Nomor resi <span class="font-mono font-bold">{{ $resi }}</span> tidak ditemukan. Pastikan nomor resi sudah benar.
                        </p>
                        <a href="{{ url('/') }}#cek-resi" class="mt-4 inline-block text-xs font-semibold text-amber-900 underline hover:no-underline">
                            ← Coba cari nomor resi lain
                        </a>
                    </div>
                @else
                    {{-- Card Hasil Tracking --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-xl">
                        {{-- Header Hasil Resi --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Nomor Resi</span>
                                <h2 class="text-2xl font-black text-[#0A3656] tracking-tight">{{ $paket->no_resi }}</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Layanan: <span class="font-semibold text-slate-700">{{ $paket->layanan ?? 'Reguler' }}</span></p>
                            </div>

                            {{-- Badge Status --}}
                            @php
                                $badgeClass = match($paket->status) {
                                    'Terkirim' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'Dikirim' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'Transit' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'Gagal Kirim', 'Retur' => 'bg-rose-100 text-rose-800 border-rose-200',
                                    default => 'bg-slate-100 text-slate-800 border-slate-200',
                                };
                            @endphp
                            <div class="self-start sm:self-auto">
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-4 py-1.5 text-xs font-bold uppercase tracking-wide {{ $badgeClass }}">
                                    <span class="h-2 w-2 rounded-full bg-current"></span>
                                    {{ $paket->status }}
                                </span>
                            </div>
                        </div>

                        {{-- Ringkasan Pengiriman --}}
                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4 rounded-xl bg-slate-50 p-4 border border-slate-100 text-sm">
                            <div>
                                <span class="text-xs text-slate-500 font-medium">Penerima</span>
                                <p class="font-bold text-slate-800 mt-0.5">{{ $paket->penerima_nama }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 font-medium">Kota Tujuan</span>
                                <p class="font-bold text-slate-800 mt-0.5">{{ $paket->kota_tujuan }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 font-medium">Telepon Penerima (Privasi)</span>
                                <p class="font-mono font-semibold text-slate-700 mt-0.5">{{ $teleponSamar }}</p>
                            </div>
                        </div>

                        {{-- Timeline Riwayat --}}
                        <div class="mt-8">
                            <h3 class="text-base font-bold text-[#0A3656] flex items-center gap-2 mb-6">
                                <svg class="h-5 w-5 text-[#1D70A2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Riwayat Perjalanan Paket</span>
                            </h3>

                            <ol class="relative border-l-2 border-slate-200 ml-3 space-y-6">
                                @forelse ($paket->riwayat as $index => $riwayat)
                                    <li class="relative pl-6">
                                        <span class="absolute -left-[9px] top-1 h-4 w-4 rounded-full border-2 border-white {{ $index === 0 ? 'bg-emerald-500 ring-4 ring-emerald-100' : 'bg-slate-300' }}"></span>
                                        <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1">
                                            <p class="text-sm font-bold text-slate-800">{{ $riwayat->status }}</p>
                                            <time class="text-xs text-slate-400 font-medium">{{ $riwayat->created_at->translatedFormat('d M Y, H:i') }} WIB</time>
                                        </div>
                                        @if ($riwayat->catatan)
                                            <p class="mt-1 text-xs text-slate-600 bg-slate-50 rounded-lg p-2.5 border border-slate-100">{{ $riwayat->catatan }}</p>
                                        @endif
                                    </li>
                                @empty
                                    <li class="pl-6 text-sm text-slate-400">Belum ada riwayat aktivitas status.</li>
                                @endforelse
                            </ol>
                        </div>
                    </div>
                @endif
            </section>
        @endif

        {{-- ===== 3 FEATURE CARDS (MENGAMBANG SEBAGIAN) ===== --}}
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 {{ $resi !== '' ? 'mt-8' : '-mt-14' }} relative z-20">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Card 1: Privasi Aman --}}
                <div class="rounded-2xl bg-white p-7 shadow-lg border border-slate-100 hover:shadow-xl transition duration-200">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-700 mb-5">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Privasi Aman</h3>
                    <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                        Pelacakan publik tanpa login dengan penyamaran nomor telepon penerima demi keamanan data.
                    </p>
                </div>

                {{-- Card 2: Dukungan COD --}}
                <div class="rounded-2xl bg-white p-7 shadow-lg border border-slate-100 hover:shadow-xl transition duration-200">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 mb-5">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Dukungan COD</h3>
                    <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                        Layanan bayar di tempat dengan biaya transparan 2% dan rekap setoran kurir akurat.
                    </p>
                </div>

                {{-- Card 3: Layanan Kilat --}}
                <div class="rounded-2xl bg-white p-7 shadow-lg border border-slate-100 hover:shadow-xl transition duration-200">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600 mb-5">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Layanan Kilat</h3>
                    <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                        Pilihan pengiriman Reguler dan Express dengan jaminan tarif zona kompetitif di Jawa Barat.
                    </p>
                </div>

            </div>
        </section>

        {{-- ===== SECTION ZONA & TARIF PENGIRIMAN ===== --}}
        <section id="tarif-zona" class="py-20 px-4 sm:px-6 lg:px-8 mx-auto max-w-7xl">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0A3656] tracking-tight">
                    Zona & Tarif Pengiriman
                </h2>
                <p class="mt-2 text-sm sm:text-base text-slate-500 max-w-xl mx-auto">
                    Tarif dasar transparan per kilogram berdasarkan kota tujuan pengiriman
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                @php
                    // Fallback data jika belum ada seeder
                    $tarifList = $zonaTarif->isNotEmpty() ? $zonaTarif : collect([
                        (object)[ 'kode_zona' => 1, 'nama_zona' => 'Bandung Raya', 'harga_per_kg' => 8000, 'cakupan_wilayah' => 'Mencakup Kota Bandung, Kabupaten Bandung, dan Kota Cimahi.' ],
                        (object)[ 'kode_zona' => 2, 'nama_zona' => 'Jawa Barat Lainnya', 'harga_per_kg' => 12000, 'cakupan_wilayah' => 'Mencakup wilayah Garut, Tasikmalaya, Ciamis, dan sekitarnya.' ],
                        (object)[ 'kode_zona' => 3, 'nama_zona' => 'Luar Jawa Barat', 'harga_per_kg' => 20000, 'cakupan_wilayah' => 'Pengiriman nasional ke berbagai kota di luar provinsi Jawa Barat.' ],
                    ]);
                @endphp

                @foreach ($tarifList as $zona)
                    <div class="rounded-2xl bg-white p-8 text-center shadow-md border border-slate-100 hover:shadow-lg transition">
                        <span class="text-xs font-extrabold tracking-widest uppercase text-blue-600">
                            ZONA {{ $zona->kode_zona }}
                        </span>
                        <h3 class="text-xl font-bold text-slate-800 mt-2">
                            {{ $zona->nama_zona }}
                        </h3>

                        <div class="mt-5 mb-5 flex items-baseline justify-center gap-1">
                            <span class="text-3xl font-black text-slate-900 tracking-tight">
                                Rp {{ number_format($zona->harga_per_kg, 0, ',', '.') }}
                            </span>
                            <span class="text-sm font-semibold text-slate-500">/ kg</span>
                        </div>

                        <p class="text-xs text-slate-500 leading-relaxed">
                            @if ($zona->kode_zona == 1)
                                Mencakup Kota Bandung, Kabupaten Bandung, dan Kota Cimahi.
                            @elseif ($zona->kode_zona == 2)
                                Mencakup wilayah Garut, Tasikmalaya, Ciamis, dan sekitarnya.
                            @else
                                Pengiriman nasional ke berbagai kota di luar provinsi Jawa Barat.
                            @endif
                        </p>
                    </div>
                @endforeach

            </div>
        </section>

        {{-- ===== SECTION TENTANG KAMI ===== --}}
        <section id="tentang-kami" class="py-16 bg-slate-100/70 border-t border-slate-200 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-4xl text-center">
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#0A3656] text-white text-xl font-bold mb-4 shadow">
                    ⚡
                </div>
                <h3 class="text-2xl font-bold text-[#0A3656]">Priangan Kilat Express</h3>
                <p class="mt-3 text-sm text-slate-600 leading-relaxed max-w-2xl mx-auto">
                    Menyediakan layanan pengiriman paket terpadu untuk wilayah Priangan dan sekitarnya. Didukung oleh kurir profesional dan sistem pemantauan terintegrasi yang menjamin paket Anda tiba dengan cepat, tepat, dan aman.
                </p>
            </div>
        </section>
    </div>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-[#0A3656] text-white py-6 px-4 text-center">
        <p class="text-xs text-slate-300">
            &copy; {{ date('Y') }} Priangan Kilat Express
        </p>
    </footer>

</body>
</html>
