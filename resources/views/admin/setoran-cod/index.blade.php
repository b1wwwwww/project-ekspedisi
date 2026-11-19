@extends('layouts.admin')

@section('title', 'Setoran COD')
@section('breadcrumb', 'Setoran COD')

@section('content')

<div class="space-y-6">

    {{-- Header halaman --}}
    <div>
        <h1 class="text-2xl font-bold text-[#0A3656]">
            Setoran COD
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Kelola pencatatan uang COD yang disetorkan oleh kurir.
        </p>
    </div>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Notifikasi error --}}
    @if (session('error'))
        <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Daftar kurir --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <h2 class="mb-4 text-lg font-semibold text-[#0A3656]">
            Pilih Kurir
        </h2>

        <form action="{{ route('admin.setoran-cod.index') }}" method="GET">
            <div class="flex flex-col gap-3 sm:flex-row">
                <select
                    name="kurir_id"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-[#0A3656] focus:outline-none"
                >
                    <option value="">-- Pilih Kurir --</option>

                    {{-- menampilkan semua kurir dari database. --}}
                    @foreach ($kurir as $item)
                        <option
                            value="{{ $item->id }}"
                            {{ request('kurir_id') == $item->id ? 'selected' : '' }}
                        >
                            {{ $item->pengguna->nama ?? 'Nama tidak tersedia' }}
                            — COD Belum Disetor:
                            Rp{{ number_format($item->cod_belum_disetor ?? 0, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>

                <button
                    type="submit"
                    class="rounded-lg bg-[#0A3656] px-6 py-3 text-sm font-semibold text-white hover:bg-[#082b45]"
                >
                    Tampilkan Paket
                </button>
            </div>
        </form>
    </div>


    {{-- Paket COD yang belum disetor --}}
    @if ($kurirDipilih)

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="text-lg font-semibold text-[#0A3656]">
                    Paket COD Belum Disetor
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Kurir: {{ $kurirDipilih->pengguna->nama ?? 'Nama tidak tersedia' }}
                </p>
            </div>

            @if ($paketBelumDisetor->isEmpty())

                <div class="rounded-lg bg-slate-50 p-6 text-center text-sm text-slate-500">
                    Tidak ada paket COD yang perlu disetor.
                </div>

            @else

                <form action="{{ route('admin.setoran-cod.store') }}" method="POST">
                    @csrf

                    <input
                        type="hidden"
                        name="kurir_id"
                        value="{{ $kurirDipilih->id }}"
                    >

                    <div class="overflow-x-auto">

                        <table class="w-full text-left text-sm">

                            <thead class="border-b bg-slate-50 text-slate-600">
                                <tr>
                                    <th class="px-4 py-3">Pilih</th>
                                    <th class="px-4 py-3">No. Resi</th>
                                    <th class="px-4 py-3">Penerima</th>
                                    <th class="px-4 py-3 text-right">Nilai COD</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">

                                @foreach ($paketBelumDisetor as $item)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <input
                                                type="checkbox"
                                                name="paket_id[]"
                                                value="{{ $item->id }}"
                                                class="paket-checkbox h-4 w-4"
                                                data-nilai="{{ $item->nilai_cod }}"
                                            >
                                        </td>

                                        <td class="px-4 py-3 font-medium text-[#0A3656]">
                                            {{ $item->no_resi }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $item->penerima_nama }}
                                        </td>

                                        <td class="px-4 py-3 text-right font-medium">
                                            Rp{{ number_format($item->nilai_cod, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    <div class="mt-6 flex flex-col gap-4 border-t pt-5 sm:flex-row sm:items-center sm:justify-between">

                        <div>
                            <p class="text-sm text-slate-500">
                                Total COD dipilih
                            </p>

                            <p id="total-cod" class="text-xl font-bold text-[#0A3656]">
                                Rp0
                            </p>
                        </div>

                        <button
                            type="submit"
                            class="rounded-lg bg-green-600 px-6 py-3 text-sm font-semibold text-white hover:bg-green-700"
                        >
                            Catat Setoran COD
                        </button>

                    </div>

                </form>

            @endif

        </div>

    @endif

    {{-- Error validasi --}}
    @if ($errors->any())
        <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Menghitung total COD yang dipilih --}}
    @if ($kurirDipilih && $paketBelumDisetor->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const checkboxes = document.querySelectorAll('.paket-checkbox');
                const totalElement = document.getElementById('total-cod');

                function hitungTotal() {
                    let total = 0;

                    checkboxes.forEach(function (checkbox) {
                        if (checkbox.checked) {
                            total += Number(checkbox.dataset.nilai);
                        }
                    });

                    totalElement.textContent = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        maximumFractionDigits: 0
                    }).format(total);
                }

                checkboxes.forEach(function (checkbox) {
                    checkbox.addEventListener('change', hitungTotal);
                });
            });
        </script>
    @endif

</div>

@endsection