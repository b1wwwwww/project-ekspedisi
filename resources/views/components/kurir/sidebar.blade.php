<aside class="fixed inset-y-0 left-0 z-40 flex h-screen w-[250px] flex-col bg-[#0A3656] px-4 py-6 text-white shadow-xl">
    <div class="mb-8 flex items-center gap-3 px-2">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#2F546F] text-xl font-bold">⚡</div>
        <div>
            <h1 class="text-sm font-extrabold tracking-wide">PRIANGAN KILAT</h1>
            <p class="text-xs text-blue-200">AREA KURIR</p>
        </div>
    </div>

    <nav class="flex-1 space-y-2">
        <a href="{{ route('kurir.penugasan.index') }}"
           class="block rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-[#1D70A2]">
            Penugasan Saya
        </a>
    </nav>

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
