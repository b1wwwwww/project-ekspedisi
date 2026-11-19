<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Manajer') - Priangan Kilat Express</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-800">

    <div class="min-h-screen">
        @include('components.manajer.sidebar')

        <div class="ml-[250px]">
            @include('components.manajer.topbar')

            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

</body>
</html>