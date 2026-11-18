@extends('layouts.admin')

@section('title', 'Edit Kurir')
@section('breadcrumb', 'Edit Kurir')

@section('content')
    <div class="max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-[#0A3656]">Edit Kurir</h1>
            <p class="mt-1 text-sm text-slate-500">Perbarui profil dan kredensial akun Kurir.</p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form action="{{ route('admin.kurir.update', $kurir) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="nama" class="mb-2 block text-sm font-semibold text-slate-700">Nama Kurir *</label>
                        <input id="nama" name="nama" value="{{ old('nama', $kurir->pengguna->nama) }}" required
                               class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]">
                        @error('nama') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="telepon" class="mb-2 block text-sm font-semibold text-slate-700">Nomor Telepon *</label>
                        <input id="telepon" name="telepon" value="{{ old('telepon', $kurir->pengguna->telepon) }}" required
                               class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]">
                        @error('telepon') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email <span class="font-normal text-slate-400">(Opsional)</span></label>
                        <input id="email" name="email" type="email" value="{{ old('email', $kurir->pengguna->email) }}"
                               class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]">
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="kode_kurir" class="mb-2 block text-sm font-semibold text-slate-700">Kode Kurir *</label>
                        <input id="kode_kurir" name="kode_kurir" value="{{ old('kode_kurir', $kurir->kode_kurir) }}" required
                               class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]">
                        @error('kode_kurir') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Password Baru <span class="font-normal text-slate-400">(Opsional)</span></label>
                        <input id="password" name="password" type="password"
                               class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]">
                        @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi Password Baru</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                               class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]">
                    </div>
                </div>

                <div class="mt-5 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    Kosongkan password jika tidak ingin mengubah kredensial. Jika diisi, Kurir wajib mengganti password pada login berikutnya.
                </div>

                <div class="mt-8 flex justify-end gap-3 border-t border-slate-100 pt-5">
                    <a href="{{ route('admin.kurir.index') }}"
                       class="rounded-lg bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-600">Batal</a>
                    <button type="submit"
                            class="rounded-lg bg-[#0A3656] px-5 py-2.5 text-sm font-semibold text-white">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
