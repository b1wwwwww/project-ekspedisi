<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lacak Paket | Priangan Kilat Express</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800">
    <header class="bg-[#06446b] text-white shadow-sm">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-5 py-4 sm:px-8">
            <a href="{{ route('pelacakan-resi.index') }}" class="flex items-center gap-3 font-bold">
                <span class="grid h-10 w-10 place-items-center rounded-lg bg-[#1478b3] text-xl text-yellow-300">⚡</span>
                <span>Priangan Kilat Express</span>
            </a>
            <span class="hidden text-sm text-slate-200 sm:block">Layanan Cek Resi Publik Tanpa Login</span>
        </div>
    </header>

    <main class="mx-auto max-w-3xl px-5 py-10 sm:py-16">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-9">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-[#06446b]">Lacak Paket Pengiriman</h1>
                <p class="mt-2 text-sm text-slate-500">Masukkan nomor resi untuk melihat status dan riwayat paket terkini.</p>
            </div>

            <form method="GET" action="{{ route('pelacakan-resi.index') }}" class="mt-6 flex flex-col gap-3 sm:flex-row">
                <label for="resi" class="sr-only">Nomor resi</label>
                <input id="resi" name="resi" value="{{ $resi }}" placeholder="Contoh: PKE-261109-0001" autocomplete="off" class="min-w-0 flex-1 rounded-lg border-slate-300 px-4 py-3 uppercase focus:border-sky-600 focus:ring-sky-600">
                <button type="submit" class="rounded-lg bg-[#1478b3] px-7 py-3 font-semibold text-white transition hover:bg-[#0d659a]">Cek Resi</button>
            </form>
            @error('resi')<p class="mt-3 text-sm text-red-600">{{ $message }}</p>@enderror
        </section>

        @if ($resi !== '' && ! $paket)
            <section class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-5 text-center text-amber-800">
                Nomor resi <strong>{{ $resi }}</strong> tidak ditemukan. Pastikan nomor resi sudah benar.
            </section>
        @endif

        @if ($paket)
            <section class="mt-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex flex-col gap-3 border-b border-slate-200 pb-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Nomor Resi</p>
                        <h2 class="mt-1 text-xl font-bold text-[#06446b]">{{ $paket->no_resi }}</h2>
                    </div>
                    <span class="w-fit rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-800">{{ $paket->status }}</span>
                </div>

                <dl class="mt-6 grid gap-4 rounded-lg bg-slate-50 p-4 text-sm sm:grid-cols-2">
                    <div><dt class="text-slate-500">Penerima</dt><dd class="mt-1 font-semibold">{{ $paket->penerima_nama }}</dd></div>
                    <div><dt class="text-slate-500">Kota Tujuan</dt><dd class="mt-1 font-semibold">{{ $paket->kota_tujuan }}</dd></div>
                    <div><dt class="text-slate-500">Telepon Penerima</dt><dd class="mt-1 font-semibold">{{ $teleponSamar }}</dd></div>
                </dl>

                <h3 class="mt-7 text-lg font-bold text-[#06446b]">Riwayat Status Paket</h3>
                <ol class="mt-5 space-y-0 border-l-2 border-slate-200 pl-5">
                    @forelse ($paket->riwayat as $riwayat)
                        <li class="relative pb-6 last:pb-0">
                            <span class="absolute -left-[1.8rem] top-1 h-3 w-3 rounded-full border-2 border-white bg-emerald-500"></span>
                            <p class="text-sm text-slate-500">{{ $riwayat->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                            <p class="mt-1 font-bold text-[#06446b]">{{ $riwayat->status }}</p>
                            @if ($riwayat->catatan)<p class="mt-1 text-sm text-slate-600">{{ $riwayat->catatan }}</p>@endif
                        </li>
                    @empty
                        <li class="text-sm text-slate-500">Belum ada riwayat status untuk paket ini.</li>
                    @endforelse
                </ol>
            </section>
        @endif
    </main>
</body>
</html>
