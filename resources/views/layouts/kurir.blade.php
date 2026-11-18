<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Kurir') | Priangan Kilat Express</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-[#F6F8FA] text-slate-800" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    <div class="min-h-screen">
        @include('components.kurir.topbar')

        <main class="mx-auto max-w-7xl p-4 sm:p-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
