@extends('layouts.admin')

@section('title', 'Data Pelanggan')
@section('breadcrumb', 'Data Pelanggan')

@section('content')

    <div class="space-y-6">

        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-[#0A3656]">
                    Data Pelanggan
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Kelola dan pantau data pelanggan Priangan Kilat Express.
                </p>
            </div>

            {{-- Tombol Tambah Pelanggan --}}
            <a href="{{ route('pelanggan.create') }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#0A3656] hover:bg-[#082b45] text-white text-sm font-semibold rounded-lg transition">
                <span class="text-lg leading-none">+</span>
                Tambah Pelanggan
            </a>
        </div>

        {{-- Konten Utama --}}
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">

            {{-- Header Tabel --}}
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="text-base font-bold text-[#0A3656]">
                    Daftar Pelanggan
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Daftar pelanggan yang terdaftar di sistem.
                </p>
            </div>

            {{-- Pencarian --}}
            <div class="p-5">
                <form action="{{ route('pelanggan.index') }}" method="GET"
                    class="flex flex-col sm:flex-row gap-3">

                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari nama atau nomor telepon..."
                        class="w-full sm:w-72 px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-[#1D70A2] focus:border-[#1D70A2]"
                    >

                    <button
                        type="submit"
                        class="px-5 py-2.5 bg-[#0A3656] hover:bg-[#082b45] text-white text-sm font-semibold rounded-lg transition">
                        Cari
                    </button>

                    <a href="{{ route('pelanggan.index') }}"
                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-lg text-center transition">
                        Reset
                    </a>

                </form>
            </div>

            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="mx-5 mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tabel Pelanggan --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">

                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">ID Pelanggan</th>
                            <th class="px-5 py-3 font-semibold">Nama</th>
                            <th class="px-5 py-3 font-semibold">Telepon</th>
                            <th class="px-5 py-3 font-semibold">Alamat</th>
                            <th class="px-5 py-3 font-semibold text-center">Total Kiriman</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @forelse ($pelanggan as $item)
                            <tr class="transition-colors duration-150 hover:bg-slate-50">

                                <td class="px-5 py-4 text-slate-500 font-medium">
                                    {{ 'CUST-' . str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                                </td>

                                <td class="px-5 py-4 font-semibold text-[#0A3656]">
                                    {{ $item->nama }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $item->telepon }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $item->alamat ?? '-' }}
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center justify-center min-w-8 px-2.5 py-1 rounded-full bg-blue-50 text-[#1D70A2] text-xs font-semibold">
                                        {{ $item->paket_count }}
                                    </span>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center">
                                    <p class="text-sm font-medium text-slate-500">
                                        Belum ada data pelanggan.
                                    </p>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-4 border-t border-slate-100">
                {{ $pelanggan->links() }}
            </div>

        </div>

    </div>

@endsection