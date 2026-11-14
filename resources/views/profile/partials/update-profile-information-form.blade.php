<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Informasi Profil
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Perbarui informasi profil akun Anda.
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Nama --}}
        <div>
            <x-input-label for="nama" value="Nama Lengkap" />

            <x-text-input
                id="nama"
                name="nama"  
                type="text"
                class="mt-1 block w-full"
                :value="old('nama', $user->nama)"
                required
                autofocus
                autocomplete="name"
            />

            <x-input-error class="mt-2" :messages="$errors->get('nama')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" value="Email" />

            <x-text-input
                id="email"
                name="email" 
                type="email"
                class="mt-1 block w-full"
                :value="old('email', $user->email)"
                autocomplete="email"
            />

            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        {{-- Telepon --}}
        <div>
            <x-input-label for="telepon" value="Nomor Telepon" />

            <x-text-input
                id="telepon"
                name="telepon"
                type="text"
                class="mt-1 block w-full"
                :value="old('telepon', $user->telepon)"
                autocomplete="tel"
            />

            <x-input-error class="mt-2" :messages="$errors->get('telepon')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Simpan</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >Tersimpan.</p>
            @endif
        </div>
    </form>
</section>