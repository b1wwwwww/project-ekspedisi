<header class="flex h-16 items-center justify-between border-b border-slate-200 bg-white px-6">
    <div>
        <h2 class="text-lg font-semibold text-slate-800">
            @yield('breadcrumb', 'Dashboard Manajer')
        </h2>
    </div>

    <div class="flex items-center gap-3">
        <div class="text-right">
            <p class="text-sm font-semibold text-slate-800">
                {{ auth()->user()->name }}
            </p>
            <p class="text-xs text-slate-500">Manajer</p>
        </div>

        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 font-semibold text-blue-700">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
    </div>
</header>