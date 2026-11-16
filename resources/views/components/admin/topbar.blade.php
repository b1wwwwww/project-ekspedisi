<header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-8 shadow-sm">

    {{-- Breadcrumb --}}
    <div>
        <div class="flex items-center gap-2 text-sm">
            <span class="text-slate-400"></span>

            <span class="text-slate-300"></span>

            <span class="font-semibold text-[#0A3656]">
                @yield('breadcrumb', 'Dashboard Admin')
            </span>
        </div>
    </div>

    {{-- Informasi Pengguna --}}
    <div class="flex items-center gap-3">

        <div class="text-right">
            <p class="text-sm font-semibold text-slate-800">
                {{ auth()->user()->name ?? 'Admin' }}
            </p>

            <p class="text-xs text-slate-500">
                Administrator
            </p>
        </div>

        {{-- Avatar --}}
        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#0A3656] text-sm font-bold text-white">
            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
        </div>

    </div>

</header>