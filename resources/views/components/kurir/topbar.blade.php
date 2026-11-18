<header class="sticky top-0 z-30 border-b border-slate-200 bg-white shadow-sm">
    <nav class="mx-auto flex min-h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#0A3656] text-sm font-bold text-white">⚡</div>

            <div class="hidden sm:block">
                <p class="text-sm font-extrabold tracking-wide text-[#0A3656]">PRIANGAN KILAT</p>
                <p class="text-xs text-slate-500">Area Kurir</p>
            </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-4">
            <a href="{{ route('kurir.penugasan.index') }}"
               class="rounded-lg px-3 py-2 text-sm font-semibold text-[#0A3656] transition hover:bg-slate-100">
                Penugasan Saya
            </a>

            <a href="{{ route('profile.edit') }}"
               class="hidden rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 sm:block">
                Profil
            </a>

            <div class="hidden h-8 w-px bg-slate-200 sm:block"></div>

            <div class="hidden text-right sm:block">
                <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->nama ?? 'Kurir' }}</p>
                <p class="text-xs text-slate-500">Kurir</p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="rounded-lg px-3 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                    Keluar
                </button>
            </form>
        </div>
    </nav>
</header>
