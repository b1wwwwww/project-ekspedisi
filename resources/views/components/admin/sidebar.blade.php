<aside class="fixed inset-y-0 left-0 z-40 flex h-screen w-[250px] flex-col bg-[#0A3656] px-4 py-6 text-white shadow-xl">

    {{-- Logo --}}
    <div class="mb-8 flex items-center gap-3 px-2">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#2F546F] text-xl font-bold text-[#0A3656]">
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
    <nav class="flex-1 space-y-7 overflow-y-auto">

        {{-- Operasional --}}
        <div>
            <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-blue-300">
                Operasional
            </p>

            <div class="space-y-1">

                <a href="{{ url('/admin') }}"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-[#1D70A2]">
                    Dashboard
                </a>

                <a href="{{ route('paket.create') }}"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-[#1D70A2]">
                    Input Paket
                </a>

                <a href="{{ route('paket.index') }}"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-[#1D70A2]">
                    Daftar Paket
                </a>

                <a href="{{ route('admin.penugasan-kurir.index') }}"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium text-blue-100 transition hover:bg-[#1D70A2]">
                    Penugasan Kurir
                </a>

                <a href="#"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium text-blue-100 transition hover:bg-[#1D70A2]">
                    Manajemen Retur
                </a>

                <a href="#"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium text-blue-100 transition hover:bg-[#1D70A2]">
                    Setoran COD
                </a>

            </div>
        </div>

        {{-- Data Master --}}
        <div>
            <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-blue-300">
                Data Master
            </p>

            <div class="space-y-1">

                <a href="{{ route('pelanggan.index') }}"
                   class="block rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-[#1D70A2]">
                    Data Pelanggan
                </a>

                <a href="#"
                   class="block rounded-lg px-3 py-2.5 text-sm font-medium text-blue-100 transition hover:bg-[#1D70A2]">
                    Data Kurir
                </a>

                <a href="#"
                   class="block rounded-lg px-3 py-2.5 text-sm font-medium text-blue-100 transition hover:bg-[#1D70A2]">
                    Tarif & Zona
                </a>

                <a href="#"
                   class="block rounded-lg px-3 py-2.5 text-sm font-medium text-blue-100 transition hover:bg-[#1D70A2]">
                    Impor CSV
                </a>

            </div>
        </div>

    </nav>

    {{-- Akun --}}
    <div class="mt-6 space-y-1 border-t border-blue-400/30 pt-5">

        <a href="{{ url('/profile') }}"
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
