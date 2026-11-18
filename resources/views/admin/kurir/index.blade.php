@extends('layouts.admin')

@section('title', 'Data Kurir')
@section('breadcrumb', 'Data Kurir')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#0A3656]">Data Kurir</h1>
                <p class="mt-1 text-sm text-slate-500">Pantau, edit, aktifkan, atau nonaktifkan akun Kurir.</p>
            </div>

            <a href="{{ route('admin.kurir.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#0A3656] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#082b45]">
                <span class="text-lg leading-none">+</span>
                Tambah Kurir
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-base font-bold text-[#0A3656]">Daftar Akun Kurir</h2>
            </div>

            <div class="p-5">
                <form action="{{ route('admin.kurir.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row">
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="Cari nama, kode Kurir, atau nomor telepon..."
                           class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-[#1D70A2] focus:ring-2 focus:ring-[#1D70A2] sm:w-96">
                    <button type="submit" class="rounded-lg bg-[#0A3656] px-5 py-2.5 text-sm font-semibold text-white">Cari</button>
                    <a href="{{ route('admin.kurir.index') }}"
                       class="rounded-lg bg-slate-100 px-5 py-2.5 text-center text-sm font-semibold text-slate-600">Reset</a>
                </form>
            </div>

            @if (session('success'))
                <div class="mx-5 mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Kode Kurir</th>
                            <th class="px-5 py-3 font-semibold">Nama</th>
                            <th class="px-5 py-3 font-semibold">Telepon</th>
                            <th class="px-5 py-3 text-center font-semibold">Jumlah Paket</th>
                            <th class="px-5 py-3 text-center font-semibold">Status</th>
                            <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($kurir as $item)
                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="px-5 py-4 font-bold text-[#0A3656]">{{ $item->kode_kurir }}</td>
                                <td class="px-5 py-4 font-semibold text-slate-800">{{ $item->pengguna->nama }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $item->pengguna->telepon ?? '-' }}</td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex min-w-8 justify-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-[#1D70A2]">
                                        {{ $item->paket_count }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if ($item->aktif)
                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Aktif</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.kurir.edit', $item) }}"
                                           class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.kurir.toggle-status', $item) }}"
                                              method="POST"
                                              onsubmit="return confirm('Ubah status akun Kurir ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="rounded-lg px-3 py-2 text-xs font-semibold text-white transition {{ $item->aktif ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                                                {{ $item->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500">Belum ada akun Kurir.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 p-4">
                {{ $kurir->links() }}
            </div>
        </div>
    </div>
@endsection
