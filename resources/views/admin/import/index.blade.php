@extends('layouts.admin')

@section('title', 'Impor Pelanggan')

@section('content')
    <div class="max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-[#0A3656]">Impor Data Pelanggan</h1>
            <p class="mt-1 text-sm text-slate-500">
                Unggah file CSV pelanggan untuk menambahkan atau memperbarui data berdasarkan nomor telepon.
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form action="{{ route('admin.import.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-5">
                    <label for="file_pelanggan" class="mb-2 block text-sm font-semibold text-slate-700">
                        File Pelanggan <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="file"
                        id="file_pelanggan"
                        name="file_pelanggan"
                        accept=".csv,text/csv"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-600 focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]"
                    >

                    <p class="mt-2 text-xs text-slate-500">
                        Format kolom: nama, telepon, alamat, kota.
                    </p>

                    @error('file_pelanggan')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror

                    <div>
                        <label for="file_kurir" class="mb-2 block text-sm font-semibold text-slate-700">
                            File Kurir <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="file"
                            id="file_kurir"
                            name="file_kurir"
                            accept=".csv,text/csv"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-600 focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]"
                        >

                        @error('file_kurir')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="file_paket_historis" class="mb-2 block text-sm font-semibold text-slate-700">
                            File Paket Historis <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="file"
                            id="file_paket_historis"
                            name="file_paket_historis"
                            accept=".csv,text/csv"
                            class="block w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-600 focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2]"
                        >

                        <p class="mt-2 text-xs text-slate-500">
                            Unggah ketiga file sekaligus. Paket historis diproses setelah pelanggan dan kurir tersedia.
                        </p>

                        @error('file_paket_historis')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end border-t border-slate-100 pt-5">
                    <button type="submit" class="rounded-lg bg-[#0A3656] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#082b45]">
                        Jalankan Impor
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
