<aside class="fixed inset-y-0 left-0 z-40 flex h-screen w-[250px] flex-col bg-[#0A3656] px-4 py-6 text-white shadow-xl">

    {{-- Logo --}}
    <div class="mb-8 flex items-center gap-3 px-2">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#2F546F] text-xl font-bold text-white">
            ⚡
        </div>

        <div>
            <h1 class="text-sm font-extrabold tracking-wide">
                PRIANGAN KILAT
            </h1>
            <p class="text-xs text-blue-200">
                EXPRESS
            </p>
        </div>
    </div>

    {{-- Navigasi --}}
    <nav class="flex-1 space-y-6 overflow-y-auto">

        {{-- Dashboard --}}
        <div>
            <p class="mb-2 px-3 text-xs font-bold uppercase tracking-wider text-blue-300">
                Utama
            </p>

            <a href="{{ route('manajer.dashboard') }}"
                class="block rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-[#1D70A2]">
                Dashboard
            </a>
        </div>

        {{-- Manajemen --}}
        <div>
            <p class="mb-2 px-3 text-xs font-bold uppercase tracking-wider text-blue-300">
                Manajemen
            </p>

            <div class="space-y-1">
                <a href="{{ route('tarif.index') }}"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-[#1D70A2]">
                    Manajemen Tarif & Zona
                </a>

                <a href="{{ route('pelanggan.index') }}"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-[#1D70A2]">
                    Data Pelanggan
                </a>

                <a href="{{ route('admin.kurir.index') }}"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-[#1D70A2]">
                    Data Kurir
                </a>
            </div>
        </div>

        {{-- Laporan --}}
        <div>
            <p class="mb-2 px-3 text-xs font-bold uppercase tracking-wider text-blue-300">
                Laporan
            </p>

            <div class="space-y-1">
                <a href="{{ route('manajer.laporan.pengiriman') }}"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-[#1D70A2]">
                    Laporan Pengiriman
                </a>

                <a href="{{ route('manajer.laporan.pendapatan-zona') }}"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-[#1D70A2]">
                    Pendapatan per Zona
                </a>

                <a href="{{ route('manajer.laporan.rekap-cod') }}"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-[#1D70A2]">
                    Rekap COD per Kurir
                </a>
            </div>
        </div>

    </nav>

    {{-- Akun --}}
    <div class="mt-6 space-y-1 border-t border-blue-400/30 pt-5">

        <a href="{{ route('profile.edit') }}"
            class="block rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-[#1D70A2]">
            Ganti Password / Profil
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                class="w-full rounded-lg px-3 py-2.5 text-left text-sm font-medium text-red-200 transition hover:bg-red-500/20">
                Keluar
            </button>
        </form>

    </div>

</aside>