@extends('layouts.kurir')

@section('title', 'Profil Kurir')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-[#0A3656]">Profil Kurir</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola informasi akun dan password Anda.</p>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                Informasi profil berhasil diperbarui.
            </div>
        @endif

        @if (session('warning'))
            <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                {{ session('warning') }}
            </div>
        @endif

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-[#0A3656]">Ganti Password</h2>

            <form method="POST" action="{{ route('password.update') }}" class="mt-5 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="mb-2 block text-sm font-semibold text-slate-700">Password Saat Ini</label>
                    <input id="current_password" name="current_password" type="password" required
                           autocomplete="current-password"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-[#0A3656] focus:ring-2 focus:ring-[#0A3656]/20">
                    @error('updatePassword.current_password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Password Baru</label>
                    <input id="password" name="password" type="password" required
                           autocomplete="new-password"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-[#0A3656] focus:ring-2 focus:ring-[#0A3656]/20">
                    @error('updatePassword.password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           autocomplete="new-password"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-[#0A3656] focus:ring-2 focus:ring-[#0A3656]/20">
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                    @if (! $user->wajib_ganti_password)
                        <a href="{{ route('kurir.penugasan.index') }}"
                           class="rounded-lg bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-600">Kembali</a>
                    @endif

                    <button type="submit"
                            class="rounded-lg bg-[#0A3656] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[#164B70]">
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
