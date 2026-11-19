<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk Sistem — {{ config('app.name', 'Priangan Kilat Express') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Figtree', sans-serif; }
    </style>
</head>
<body class="antialiased" style="background: #eef2f7;">

    {{-- ===== Navbar ===== --}}
    <nav class="bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg text-base font-bold text-white"
                 style="background: #0A3656;">
                ⚡
            </div>
            <span class="text-sm font-bold text-slate-800">Priangan Kilat Express</span>
        </div>
        <a href="{{ url('/') }}" class="text-sm text-slate-500 hover:text-slate-700 transition">
            ← Kembali ke Beranda
        </a>
    </nav>

    {{-- ===== Konten Utama ===== --}}
    <div class="flex min-h-[calc(100vh-57px)] items-center justify-center px-4 py-10">

        <div class="w-full max-w-sm">

            {{-- Card --}}
            <div class="rounded-2xl bg-white px-8 py-8 shadow-md border border-slate-100">

                {{-- Judul --}}
                <div class="mb-6 text-center">
                    <h1 class="text-2xl font-bold text-slate-800">Masuk Sistem</h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Pilih peran akses Anda untuk melanjutkan
                    </p>
                </div>

                {{-- Tab Selector --}}
                <div class="mb-6 flex rounded-lg border border-slate-200 p-0.5 bg-slate-50">
                    <button
                        id="tab-admin"
                        type="button"
                        onclick="switchTab('admin')"
                        class="flex-1 rounded-md py-2 text-sm font-semibold transition-all duration-200"
                    >
                        Admin / Manajer
                    </button>
                    <button
                        id="tab-kurir"
                        type="button"
                        onclick="switchTab('kurir')"
                        class="flex-1 rounded-md py-2 text-sm font-semibold transition-all duration-200"
                    >
                        Kurir (Web)
                    </button>
                </div>

                {{-- Alert error --}}
                @if ($errors->any())
                    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- ===== Form Admin / Manajer ===== --}}
                <div id="panel-admin">
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Email / Username Staf
                            </label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="admin@priangankilat.com"
                                class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            >
                        </div>

                        <div>
                            <label for="password-admin" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Password
                            </label>
                            <input
                                id="password-admin"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            >
                        </div>

                        <button
                            type="submit"
                            class="mt-1 w-full rounded-lg py-2.5 text-sm font-bold text-white transition hover:opacity-90 active:scale-[0.98]"
                            style="background: #0A3656;"
                        >
                            Masuk ke Panel Staf
                        </button>

                    </form>

                    <p class="mt-4 text-center text-xs text-slate-400">
                        Akses khusus <span class="text-slate-600 font-medium">Admin Loket</span> dan
                        <span class="text-slate-600 font-medium">Manajer Eksekutif</span>.
                    </p>
                </div>

                {{-- ===== Form Kurir ===== --}}
                <div id="panel-kurir" class="hidden">
                    <form method="POST" action="{{ route('kurir.login.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="kode_kurir" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Kode Kurir
                            </label>
                            <input
                                id="kode_kurir"
                                type="text"
                                name="kode_kurir"
                                value="{{ old('kode_kurir') }}"
                                required
                                autocomplete="username"
                                placeholder="KUR-01"
                                class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            >
                        </div>

                        <div>
                            <label for="password-kurir" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Password <span class="text-slate-400 font-normal">(Min. 8 Karakter)</span>
                            </label>
                            <input
                                id="password-kurir"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            >
                        </div>

                        <button
                            type="submit"
                            class="mt-1 w-full rounded-lg py-2.5 text-sm font-bold text-white transition hover:opacity-90 active:scale-[0.98]"
                            style="background: #1D70A2;"
                        >
                            Masuk ke Web Kurir
                        </button>

                    </form>

                    <p class="mt-4 text-center text-xs text-slate-400 leading-relaxed">
                        Kurir wajib mengganti password pada login pertama atau
                        setelah di-reset Admin.
                    </p>
                </div>

            </div>

            {{-- Footer --}}
            <p class="mt-6 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} Priangan Kilat Express &bull; SMKN 1 Katapang
            </p>

        </div>
    </div>

    <script>
        // Tentukan tab aktif awal (dari error session jika ada)
        const hasKurirErrors = {{ $errors->has('kode_kurir') ? 'true' : 'false' }};
        const initialTab = hasKurirErrors
            ? 'kurir'
            : ('{{ session("login_tab", "admin") }}' === 'kurir' ? 'kurir' : 'admin');

        function switchTab(tab) {
            const tabAdmin   = document.getElementById('tab-admin');
            const tabKurir   = document.getElementById('tab-kurir');
            const panelAdmin = document.getElementById('panel-admin');
            const panelKurir = document.getElementById('panel-kurir');

            // Reset semua tab
            const inactiveStyle = 'text-slate-500 hover:text-slate-700';
            const activeStyle   = 'bg-white text-slate-800 shadow-sm';

            tabAdmin.className = `flex-1 rounded-md py-2 text-sm font-semibold transition-all duration-200 ${tab === 'admin' ? activeStyle : inactiveStyle}`;
            tabKurir.className = `flex-1 rounded-md py-2 text-sm font-semibold transition-all duration-200 ${tab === 'kurir' ? activeStyle : inactiveStyle}`;

            panelAdmin.classList.toggle('hidden', tab !== 'admin');
            panelKurir.classList.toggle('hidden', tab !== 'kurir');
        }

        // Set tab awal
        switchTab(initialTab);
    </script>

</body>
</html>
