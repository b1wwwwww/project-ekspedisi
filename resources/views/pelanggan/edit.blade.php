@extends('layouts.admin')

@section('title', 'Edit Pelanggan')
@section('breadcrumb', 'Edit Pelanggan')

@section('content')

    <div class="max-w-3xl">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-[#0A3656]">
                Edit Pelanggan
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Perbarui informasi pelanggan Priangan Kilat Express.
            </p>
        </div>

        {{-- Form --}}
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">

            <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-5">

                    {{-- Nama --}}
                    <div>
                        <label for="nama" class="block text-sm font-semibold text-slate-700 mb-2">
                            Nama Pelanggan <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            value="{{ old('nama', $pelanggan->nama) }}"
                            placeholder="Masukkan nama pelanggan"
                            class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-[#1D70A2] focus:border-[#1D70A2]"
                        >

                        @error('nama')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Telepon --}}
                    <div>
                        <label for="telepon" class="block text-sm font-semibold text-slate-700 mb-2">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            id="telepon"
                            name="telepon"
                            value="{{ old('telepon', $pelanggan->telepon) }}"
                            placeholder="Contoh: 081234567890"
                            class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-[#1D70A2] focus:border-[#1D70A2]"
                        >

                        @error('telepon')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label for="alamat" class="block text-sm font-semibold text-slate-700 mb-2">
                            Alamat Utama
                            <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>

                        <textarea
                            id="alamat"
                            name="alamat"
                            rows="3"
                            placeholder="Masukkan alamat utama pelanggan"
                            class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-[#1D70A2] focus:border-[#1D70A2]"
                        >{{ old('alamat', $pelanggan->alamat) }}</textarea>

                        @error('alamat')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Tombol --}}
                <div class="flex justify-end gap-3 mt-8 pt-5 border-t border-slate-100">

                    <a
                        href="{{ route('pelanggan.index') }}"
                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-lg transition">
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="px-5 py-2.5 bg-[#0A3656] hover:bg-[#082b45] text-white text-sm font-semibold rounded-lg transition">
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection