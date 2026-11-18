<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('kurir.login.store') }}">
        @csrf

        <!-- Kode Kurir -->
        <div>
            <x-input-label for="kode_kurir" value="Kode Kurir" />

            <x-text-input
                id="kode_kurir"
                class="block mt-1 w-full"
                type="text"
                name="kode_kurir"
                :value="old('kode_kurir')"
                required
                autofocus
                autocomplete="username"
            />

            <x-input-error :messages="$errors->get('kode_kurir')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Password" />

            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    name="remember"
                >
                <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            <a
                href="{{ route('login') }}"
                class="underline text-sm text-gray-600 hover:text-gray-900"
            >
                Login Admin
            </a>

            <x-primary-button>
                Login Kurir
            </x-primary-button>
        </div>
    </form>
    
</x-guest-layout>